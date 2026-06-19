<?php

/**
 * Represents a renderable link card component.
 */
class LinkCard
{
    private string $title;
    private string $url;
    private string $description;
    private array $tags;
    private ?string $imageUrl;

    /**
     * @param string $title       Card title
     * @param string $url         Target URL
     * @param string $description Short description
     * @param array  $tags        List of tag strings
     * @param string|null $imageUrl Optional image URL
     */
    public function __construct(
        string $title,
        string $url,
        string $description = '',
        array $tags = [],
        ?string $imageUrl = null
    ) {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->tags = $tags;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Return the card as an escaped HTML fragment.
     *
     * @return string Safe HTML string
     */
    public function toHtml(): string
    {
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDesc = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $tagHtml = '';
        if (!empty($this->tags)) {
            $tagItems = [];
            foreach ($this->tags as $tag) {
                $safeTag = htmlspecialchars($tag, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $tagItems[] = '<span class="card-tag">' . $safeTag . '</span>';
            }
            $tagHtml = '<div class="card-tags">' . implode(' ', $tagItems) . '</div>';
        }

        $imageHtml = '';
        if ($this->imageUrl !== null) {
            $safeImageUrl = htmlspecialchars($this->imageUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $imageHtml = '<img class="card-image" src="' . $safeImageUrl . '" alt="' . $escapedTitle . '" />';
        }

        return '<div class="link-card">'
            . $imageHtml
            . '<a class="card-link" href="' . $escapedUrl . '" rel="noopener noreferrer" target="_blank">'
            . '<h3 class="card-title">' . $escapedTitle . '</h3>'
            . '</a>'
            . '<p class="card-description">' . $escapedDesc . '</p>'
            . $tagHtml
            . '</div>';
    }

    /**
     * Static factory: create a card from an associative array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? 'Untitled',
            $data['url'] ?? '#',
            $data['description'] ?? '',
            $data['tags'] ?? [],
            $data['imageUrl'] ?? null
        );
    }
}

/**
 * Render a list of LinkCard objects into a grouped HTML block.
 *
 * @param array $cards Array of LinkCard instances
 * @return string Escaped HTML
 */
function renderLinkCards(array $cards): string
{
    $html = '<div class="link-card-list">';
    foreach ($cards as $card) {
        $html .= $card->toHtml();
    }
    $html .= '</div>';
    return $html;
}

// --- Example usage ---

$sampleCards = [
    LinkCard::fromArray([
        'title' => '爱游戏平台',
        'url' => 'https://msite-aiyouxi.com.cn',
        'description' => '发现最新最热的游戏资讯与社区动态。爱游戏，汇聚精彩瞬间。',
        'tags' => ['游戏', '社区', '资讯'],
    ]),
    LinkCard::fromArray([
        'title' => '爱游戏攻略站',
        'url' => 'https://msite-aiyouxi.com.cn/guides',
        'description' => '专业攻略、深度评测，助你成为游戏高手。',
        'tags' => ['攻略', '评测'],
    ]),
];

// Output the rendered HTML (normally you would echo this in a view)
$renderedHtml = renderLinkCards($sampleCards);
// echo $renderedHtml;