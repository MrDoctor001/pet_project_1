<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class Cart
{
    /**
     * Ключ, под которым корзина хранится в сессии
     */
    private const SESSION_KEY = 'cart';

    /**
     * Количество каждого товара в корзине: [id товара => количество]
     *
     * @return array<int, int>
     */
    public function quantities(): array
    {
        return session(self::SESSION_KEY, []);
    }

    /**
     * Общее количество штук в корзине (без снятых с продажи товаров)
     */
    public function count(): int
    {
        return array_sum($this->availableQuantities());
    }

    /**
     * Нет ли в корзине товаров, которые можно купить
     */
    public function isEmpty(): bool
    {
        return $this->availableQuantities() === [];
    }

    /**
     * Добавляет товар в корзину, но не больше остатка на складе
     */
    public function add(Product $product, int $quantity): void
    {
        $quantities = $this->quantities();

        $newQuantity = ($quantities[$product->id] ?? 0) + $quantity;

        $quantities[$product->id] = min($newQuantity, $product->stock);

        $this->save($quantities);
    }

    /**
     * Устанавливает новое количество товара
     */
    public function update(Product $product, int $quantity): void
    {
        $quantities = $this->quantities();

        $quantities[$product->id] = $quantity;

        $this->save($quantities);
    }

    /**
     * Удаляет товар из корзины
     */
    public function remove(Product $product): void
    {
        $quantities = $this->quantities();

        unset($quantities[$product->id]);

        $this->save($quantities);
    }

    /**
     * Очищает корзину
     */
    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Активные товары, которые лежат в корзине
     *
     * @param  bool  $lockForUpdate  Заблокировать строки товаров до конца транзакции
     * @return Collection<int, Product>
     */
    public function products(bool $lockForUpdate = false): Collection
    {
        return Product::query()
            ->whereIn('id', array_keys($this->quantities()))
            ->where('is_active', true)
            ->when($lockForUpdate, fn ($query) => $query->lockForUpdate())
            ->with('category')
            ->get();
    }

    /**
     * Сумма к оплате
     */
    public function total(): float
    {
        $quantities = $this->quantities();

        $total = 0;

        foreach ($this->products() as $product) {
            $total += $product->price * $quantities[$product->id];
        }

        return $total;
    }

    /**
     * Количество только тех товаров, которые всё ещё продаются
     *
     * @return array<int, int>
     */
    private function availableQuantities(): array
    {
        $quantities = $this->quantities();

        if ($quantities === []) {
            return [];
        }

        $activeProductIds = Product::query()
            ->whereIn('id', array_keys($quantities))
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        return array_intersect_key($quantities, array_flip($activeProductIds));
    }

    /**
     * Сохраняет корзину в сессию
     *
     * @param  array<int, int>  $quantities
     */
    private function save(array $quantities): void
    {
        session()->put(self::SESSION_KEY, $quantities);
    }
}
