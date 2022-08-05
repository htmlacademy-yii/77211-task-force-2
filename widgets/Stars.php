<?php

namespace app\widgets;

use yii\base\Widget;

class Stars extends Widget
{
    public float $rating;

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->rating = round($this->rating);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            if ($this->rating >= $i) {
                echo '<span class="fill-star">&nbsp;</span>';
            } else {
                echo '<span>&nbsp;</span>';
            }
        }
    }
}