<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;

class SignboardService
{
    public static function getDistributions($signboard): array
    {
        // find ratings distributions
        $totalReviews = $signboard->totalReviews();
        $distributions = [
            5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0,
        ];

        $reviews = $signboard->reviews;
        $reviewers = User::query()->whereIn('id', $reviews->pluck('user_id')->toArray())->get(['id', 'firstname', 'lastname']);
        $reviews->map(function ($review) use (&$distributions, $reviewers) {
            $ar = $review->ratings->avg('value');
            $reviewRating = ratingFormat($ar, 0);
            $distributions[(int)$reviewRating] += 1;
            $review->user = $reviewers->where('id', $review->user_id)->first();
            $review->average_rating = ratingFormat($ar);
            $review->created_at_str = Carbon::parse($review->created_at)->diffForHumans();
        });

        foreach ($distributions as $key => $value) {
            if($totalReviews == 0){
                $distributions[$key] = 0;
            }
            else{
                $distributions[$key] = ((int)$value / (int)$totalReviews) * 100;
            }
        }

        return $distributions;
    }
}
