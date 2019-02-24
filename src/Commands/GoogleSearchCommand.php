<?php namespace src\Commands;

use DOMDocument;
use DOMXPath;

class GoogleSearchCommand implements Command
{
    protected $search_url = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q=';
    /**
     * @var string
     */
    protected $description = 'Get top 5 search results from google search.';

    /**
     * @var string
     */
    protected $handleMessage = "\n resolving search results....\n\n";

    /**
     *  Get top 5 search results from google search:
     *
     * @param $string
     *
     * @return string
     */
    public function handle($string): string
    {
        $output = '';
        $string  = urlencode($string);
        $url     = $this->search_url . $string . '&oq=' . $string . '';
        $html    = $this->fetchHtml($url);
        $results = $this->filterResults($html);

        if ($results->length) {
            $output = $this->getTop5Links($results);
        }

        return $output;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getHandleMessage(): string
    {
        return $this->handleMessage;
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    private function fetchHtml($url)
    {
        $curl_query = curl_init($url);
        curl_setopt($curl_query, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($curl_query);
    }

    private function filterResults($html)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        @$dom->loadHTML($html);

        $domQuery = new DOMXPath($dom);

        return $domQuery->query('//h3[@class="r"]//a');
    }

    private function getTop5Links(\DOMNodeList $results): string
    {
        $links   = '';
        $hosts   = [];
        $counter = 0;
        for ($i = 0; $i < $results->length; $i++) {
            if ($counter > 4) {
                break;
            }
            $href = ($results->item($i)->getAttribute('href'));
            if (!strstr($href, '/search?')) {
                $href = (str_replace('/url?q=', '', $href)) . "\n\n";
                $host = parse_url($href)['host'];

                if (!in_array($host, $hosts)) {
                    array_push($hosts, parse_url($href)['host']);
                    $links .= "\n (Title) => " . $results->item($i)->nodeValue . "  (Link) =>  " . $href . "\n\n";
                    $counter++;
                }
            }
        }

        return $links;
    }

}