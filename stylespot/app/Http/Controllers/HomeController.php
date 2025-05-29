<?php

namespace App\Http\Controllers;
use App\Models\Category;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('latestProduct')->get();

        return view('pages.home', ['categories' => $categories]);
    }

    public function indexMen()
    {
        $categories = $this->getCategoriesByGender('male');
        return view('pages.home-men', ['categories' => $categories]);
    }

    public function indexWomen()
    {
        $categories = $this->getCategoriesByGender('female');
        return view('pages.home-women', ['categories' => $categories]);
    }

    private function getCategoriesByGender($gender)
    {
        return Category::whereHas('products', function ($query) use ($gender) {
            $query->where('gender', $gender);
        })
            ->with([
                $gender == 'male' ? 'latestMaleProduct' : 'latestFemaleProduct' => function ($query) use ($gender) {
                    $query->where('gender', $gender);
                }
            ])
            ->take(4)
            ->get();
    }
}
