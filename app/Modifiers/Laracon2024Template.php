<?php

namespace App\Modifiers;

use App\States\GameState;
use Illuminate\Support\Carbon;

class Laracon2024Template
{
    public Carbon $starts_at;

    public function __construct(GameState $game)
    {
        $this->starts_at = $game->starts_at;
    }

    const CODES = [
        "96191483", "21072147", "30387481", "97831546", "63014194", "58683158", "17333820", "66835784", "62674901", "24746852", "56942457", "52257298", "67107245", "38603075", "14013186", "69007377", "81611126", "46631383", "21471392", "77914845", "45919782", "28892472", "50834642", "48881269", "98325890", "43234025", "78720917", "32065351", "67868850", "58364953", "94862528", "20231524", "19620193", "35228161", "67803665", "67352057", "41192439", "60008868", "53956226", "59273619", "51000301", "37099386", "20583421", "31902691", "24268161", "95917113", "27655309", "93077142", "12631322", "15091709", "68530172", "25316627", "25844491", "72469002", "92237930", "97982412", "89589526", "49623335", "20776527", "19027592", "51665455", "65126726", "83279514", "38481544", "39947295", "21692306", "95172806", "68087888", "29416243", "98491202", "64510866", "84103084", "22292283", "41436090", "29567324", "43163189", "84841309", "71031397", "71452297", "44339229", "16782344", "69509147", "33038483", "84026955", "44386505", "86401178", "80800304", "44154719", "56337782", "84442566", "39305194", "28343557", "32216852", "35757726", "99043373", "75116453", "87072509", "11649802", "47042202", "52029167", "91961461", "87995626", "16283489", "39682899", "99565630", "92242614", "34326902", "99979438", "26037193", "77310660"
    ];

    public function modifiers()
    {
        // assume game starts at 5am the first day of the conference
        return [
            [
                'slug' => 'signing-bonus',
                'title' => 'Signing Bonus',
                'description' => 'If you refer a player who joins the game while Signing Bonus is active, no one can downvote you for 1 hour.',
                // starts at 5am the first day
                'starts_at' => $this->starts_at->copy(),
                // ends at 1pm of the first day
                'ends_at' => $this->starts_at->copy()->addHours(8),
            ],
            [
                'slug' => 'double-down',
                'title' => 'Double Down',
                'description' => 'Votes from ballots count double.',
                // starts at 1pm of the first day
                'starts_at' => $this->starts_at->copy()->addHours(8),
                // ends at 5pm of the first day
                'ends_at' => $this->starts_at->copy()->addHours(12),
            ],
            [
                'slug' => 'buddy-system',
                'title' => 'Buddy System',
                'description' => 'If you and another player upvote each other while Buddy System is active, you will each receive an extra upvote (only works once per Buddy).',
                // starts at 5pm of the first day
                'starts_at' => $this->starts_at->copy()->addHours(12),
                // ends at 5am of the final day
                'ends_at' => $this->starts_at->copy()->addHours(24),
            ],
            [
                'slug' => 'first-shall-be-last',
                'title' => 'The First Shall Be Last',
                'description' => 'You cannot upvote players with positive scores, or downvote players with negative scores.',
                // starts at 5am of the final day
                'starts_at' => $this->starts_at->copy()->addHours(24),
                // ends at 12pm of the final day
                'ends_at' => $this->starts_at->copy()->addHours(31),
            ],
            [
                'slug' => 'blackout',
                'title' => 'Blackout',
                'description' => 'The scoreboard is hidden.',
                // starts at 12pm of the final day
                'starts_at' => $this->starts_at->copy()->addHours(31),
                // ends at 2pm of the final day
                'ends_at' => $this->starts_at->copy()->addHours(33),
            ],
            [
                'slug' => 'double-down',
                'title' => 'Double Down',
                'description' => 'Votes from ballots count double.',
                // starts at 2pm of the final day
                'starts_at' => $this->starts_at->copy()->addHours(33),
                // ends at 5pm of the final day
                'ends_at' => $this->starts_at->copy()->addHours(36),
            ],
        ];
    }
}
