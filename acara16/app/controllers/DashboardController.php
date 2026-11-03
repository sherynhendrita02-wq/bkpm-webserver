<?php

class DashboardController
{
    public function index(): void
    {
        echo "<h1>Dashboard</h1>";
        echo "<p>Selamat datang, Admin</p>";
        echo "<a href='/acara6/public/logout'>Logout</a>";
    }
}