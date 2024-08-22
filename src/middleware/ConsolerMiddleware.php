<?php

namespace clie\middleware;

use Meridia\Helpers\Console;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\View\Requirements;
use clie\Consoler;

class ConsolerMiddleware implements HTTPMiddleware
{
    public function process(HTTPRequest $request, callable $delegate)
    {
        $response = $delegate($request);
        if ($response) {
            $this->afterRequest($request, $response);
        }

        return $response;
    }
    /**
     *
     * @param HTTPRequest  $request
     * @param HTTPResponse $response
     * @return void
     */
    protected function afterRequest(
        HTTPRequest $request,
        HTTPResponse $response
    ) {
        // Don't apply to assets
        $dir = defined("ASSETS_DIR") ? ASSETS_DIR : "assets";
        if (strpos($request->getURL(), "$dir/") === 0) {
            return;
        }

        $dump = Consoler::dump();

        $script = "<script type='module'>{$dump}</script>";

        // Inject init script into the HTML response
        $body = (string) $response->getBody();

        if (strpos($body, "</body>") !== false) {
            if (Requirements::get_write_js_to_body()) {
                // Replace the last occurence of </body>
                $pos = strrpos($body, "</body>");
                if ($pos !== false) {
                    $body = substr_replace(
                        $body,
                        $script . "</body>",
                        $pos,
                        strlen("</body>")
                    );
                }
            } else {
                // Replace the first occurence of </head>
                $pos = strpos($body, "</head>");
                if ($pos !== false) {
                    $body = substr_replace(
                        $body,
                        $script . "</head>",
                        $pos,
                        strlen("</head>")
                    );
                }
            }
            $response->setBody($body);
        }
    }
}
