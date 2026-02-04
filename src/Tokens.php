<?php

declare(strict_types=1);

namespace Moinframe\Moments;

use Kirby\Cms\User;

class Tokens
{
    protected const WORDS = [
        'able',   'acid',   'aged',   'also',   'area',   'army',   'away',   'baby',
        'back',   'ball',   'band',   'bank',   'base',   'bath',   'bean',   'bear',
        'beat',   'been',   'bell',   'belt',   'bend',   'best',   'bike',   'bird',
        'bite',   'blow',   'blue',   'blur',   'boat',   'body',   'bold',   'bomb',
        'bond',   'bone',   'book',   'boot',   'born',   'boss',   'bowl',   'bulk',
        'burn',   'busy',   'cafe',   'cage',   'cake',   'calm',   'came',   'camp',
        'cape',   'card',   'care',   'cart',   'case',   'cash',   'cast',   'cave',
        'chat',   'chip',   'chop',   'city',   'clad',   'clan',   'clay',   'clip',
        'club',   'clue',   'coal',   'coat',   'code',   'coil',   'coin',   'cold',
        'colt',   'come',   'cook',   'cool',   'cope',   'copy',   'cord',   'core',
        'corn',   'cost',   'cozy',   'crew',   'crop',   'crow',   'cube',   'cult',
        'curl',   'cute',   'dale',   'dame',   'damp',   'dare',   'dark',   'dart',
        'dash',   'data',   'dawn',   'deal',   'deck',   'deep',   'deer',   'demo',
        'deny',   'desk',   'dial',   'dice',   'dime',   'dine',   'disc',   'dock',
        'dome',   'door',   'dose',   'dove',   'down',   'drag',   'draw',   'drop',
        'drum',   'dual',   'duck',   'dude',   'duke',   'dune',   'dusk',   'dust',
        'duty',   'each',   'earl',   'earn',   'ease',   'east',   'easy',   'edge',
        'edit',   'epic',   'even',   'ever',   'evil',   'exam',   'exit',   'face',
        'fact',   'fade',   'fail',   'fair',   'fall',   'fame',   'fang',   'farm',
        'fast',   'fate',   'fawn',   'fear',   'feat',   'feed',   'feel',   'felt',
        'fern',   'film',   'find',   'fine',   'fire',   'firm',   'fish',   'flag',
        'flat',   'fled',   'flip',   'flow',   'foam',   'fold',   'folk',   'fond',
        'font',   'food',   'foot',   'ford',   'fork',   'form',   'fort',   'foul',
        'fowl',   'free',   'frog',   'from',   'fuel',   'full',   'fund',   'fury',
        'fuse',   'gain',   'gale',   'game',   'gang',   'gate',   'gave',   'gaze',
        'gear',   'gift',   'girl',   'glad',   'glow',   'glue',   'goat',   'gold',
        'golf',   'gone',   'good',   'grab',   'gray',   'grew',   'grid',   'grim',
        'grin',   'grip',   'grow',   'gulf',   'guru',   'gust',   'half',   'hall',
        'halt',   'hang',   'hare',   'harm',   'harp',   'hash',   'have',   'hawk',
        'haze',   'head',   'heap',   'heat',   'held',   'helm',   'help',   'herb',
        'herd',   'hero',   'hide',   'high',   'hike',   'hill',   'hint',   'hold',
        'hole',   'home',   'hood',   'hook',   'hope',   'horn',   'host',   'hump',
    ];

    protected static function generateToken(): string
    {
        $bytes = random_bytes(6);
        $words = [];
        for ($i = 0; $i < 6; $i++) {
            $words[] = static::WORDS[ord($bytes[$i])];
        }
        return implode('-', $words);
    }

    /**
     * Creates a new API token for a user
     * @return array{token: string, entry: array{id: string, name: string, created: string}}
     */
    public static function create(string $userId, string $name): array
    {
        $user = kirby()->user($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $token = static::generateToken();
        $hash = hash('sha256', $token);
        $id = bin2hex(random_bytes(4));

        $entry = [
            'id' => $id,
            'name' => $name,
            'hash' => $hash,
            'created' => date('Y-m-d H:i:s'),
        ];

        $tokens = static::readTokens($user);
        $tokens[] = $entry;

        kirby()->impersonate('kirby');
        $user->update(['moments_tokens' => $tokens]);

        return [
            'token' => $token,
            'entry' => [
                'id' => $entry['id'],
                'name' => $entry['name'],
                'created' => $entry['created'],
            ],
        ];
    }

    /**
     * Deletes a token by ID from a user's account
     */
    public static function delete(string $userId, string $tokenId): void
    {
        $user = kirby()->user($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $tokens = static::readTokens($user);
        $tokens = array_values(array_filter(
            $tokens,
            fn(array $t) => $t['id'] !== $tokenId
        ));

        kirby()->impersonate('kirby');
        $user->update(['moments_tokens' => $tokens]);
    }

    /**
     * Lists all tokens for a user (without hashes)
     * @return array<int, array{id: string, name: string, created: string}>
     */
    public static function list(string $userId): array
    {
        $user = kirby()->user($userId);
        if (!$user) {
            return [];
        }

        return array_map(
            fn(array $t) => [
                'id' => $t['id'],
                'name' => $t['name'],
                'created' => $t['created'],
            ],
            static::readTokens($user)
        );
    }

    /**
     * Verifies a raw token against all users' stored hashes
     * @return User|null The user who owns the token, or null
     */
    public static function verify(string $rawToken): ?User
    {
        $hash = hash('sha256', $rawToken);

        foreach (kirby()->users() as $user) {
            foreach (static::readTokens($user) as $entry) {
                if (hash_equals($entry['hash'], $hash)) {
                    return $user;
                }
            }
        }

        return null;
    }

    /**
     * Reads the moments_tokens field from a user's content
     * @return array<int, array{id: string, name: string, hash: string, created: string}>
     */
    protected static function readTokens(User $user): array
    {
        $field = $user->content()->get('moments_tokens');

        if (!$field || $field->isEmpty()) {
            return [];
        }

        $data = $field->yaml();

        return is_array($data) ? $data : [];
    }
}
