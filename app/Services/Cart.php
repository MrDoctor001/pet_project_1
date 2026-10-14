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
     * Общее количество штук в корзине
     */
    public function count(): int
    {
        return array_sum($this->quantities());
    }

    /**
     * Пуста ли корзина
     */
    public function isEmpty(): bool
    {
        return $this->quantities() === [];
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
     * @return Collection<int, Product>
     */
    public function products(): Collection
    {
        return Product::query()
            ->whereIn('id', array_keys($this->quantities()))
            ->where('is_active', true)
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
     * Сохраняет корзину в сессию
     *
     * @param  array<int, int>  $quantities
     */
    private function save(array $quantities): void
    {
        session()->put(self::SESSION_KEY, $quantities);
    }
}
