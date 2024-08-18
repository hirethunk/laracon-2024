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
        'GO1VCQJ0OQ', 'B5XWKWRBC0', 'BLQ70T0K62', 'FYHJQJ1MNR', 'NTAB3SNSZ1', '8TUB3I0JVU', 'BVR3LG8JVR',
        'XPDD10IMHI', 'C65XFBVYEE', 'XG1R9TME8N', 'LX9UGDSWA5', 'JDD3OJTBUV', 'WZFL7XGZGT', '3NWU16XIUY',
        'INKGJ9AQTA', 'G7B2Y9BEYD', 'JVVDNRN21C', 'DYH1HO4Q65', 'UGX2T2NZI5', 'OJGHOQ47M0', 'DXAMADY31W',
        'RWM20AKI63', 'PKT3O3OASD', 'S5RN5H5EH7', 'OPUH1HFT81', '03SJ24CZCF', 'X2D9FSH2FP', '1EGGDDJHBL',
        '9XCU68VWDE', 'BDS6K74TAF', 'UCMJARXIA5', 'J4URN4T272', '3QOO7WS3T7', 'EIQ1GKILHA', 'OWZ8MRH31F',
        'SLTUHX77GJ', 'QYZ1EWUE5A', 'UDN9ED1ZB3', 'H0E1TUGMD3', 'FSPBMD0TVW', 'T30EXQBQFJ', 'WF193DF8T6',
        '66VIJJIC2R', 'SALU5XXBDM', 'UAETD81NBB', '8ELJ41VU6D', 'E990VGUTV5', 'TL5EE1PQ6I', '1WN0BR91M8',
        'MFO7H5GSZ0', 'C33K57DDNU', 'T7ZAM3NC7F', 'YAGRF2S4ER', 'SVP74MOMGZ', '1W7MJ7GASB', 'N1N9UBYP9O',
        'Z15T2YMK1J', 'B3GL9NHHF4', 'CCZDM24VMX', 'CSAGQ7DX5L', 'FRMY0LVSQR', 'AJDC8EB0Q8', '1EEV8IBLU8',
        'GS8G1OXIDV', 'R20Y0U5EC3', 'SUV3C8FFEO', 'E9UJRUSXV0', '4UKRY0TT8S', '7OA8IQ39AF', 'Z0Z4DSCGLF',
        'XDTITYVUKX', 'F13OFFBVQQ', '87LN42HV9S', 'FO2LXN33RY', 'HM647HTD0I', '1F2UD5NNRK', 'DN7HEFWKCQ',
        'Q4P1E6HSA1', '5LNF56BL4V', 'HMHBAHYIN7', '4SBX4OBSZS', '6EKRU3KQ2G', 'ZT86I83PS0', '9IGEI52OCW',
        'Z3PKKACGXA', 'EX18T6QZZ9', 'RBZSH2KV0P', '9LIWDESOLR', 'OMIOYT3YFM', '0USRFL9QOA', 'SSVIR9O0SG',
        'B6Z326AFOA', '88IB6GRIHN', 'AJQ6SHB0OV', '7SDH6UGUMP', 'PIWQYIK0RX', 'DTZCH6TE5Z', 'RM61LHRYPN',
        'GZPPJUABOX', '9QUZ7JN1BJ',
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
