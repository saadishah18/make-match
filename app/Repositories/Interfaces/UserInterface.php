<?php


namespace App\Repositories\Interfaces;

interface UserInterface
{
    public function get_all(): \Illuminate\Http\JsonResponse;

    public function get_one($id): \Illuminate\Http\JsonResponse;

    public function add(): \Illuminate\Http\JsonResponse;

    public function update($model);

    public function remove($id): \Illuminate\Http\JsonResponse;

    public function qrCode($user);

    public function userTransactions();
}
