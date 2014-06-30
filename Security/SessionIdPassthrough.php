<?php

namespace AC\LoginConvenienceBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SessionIdPassthrough
{
    private $key = null;
    private $targetUrl = null;

    public function extractParams(Request $request)
    {
        $this->key = $request->get('key');
        if (is_null($this->key)) {
            throw new \DomainException("No key found");
        }

        $this->targetUrl = $request->get('target_url');
        if (is_null($this->targetUrl)) {
            throw new \DomainException("No target found");
        }
    }

    public function appendParamsToQuery(Request $request)
    {
        $request->query->set("key", $this->key);
        $request->query->set("target_url", $this->targetUrl);
    }

    public function generateRedirect($data)
    {
        if (isset($data['sessionId'])) {
            $data['sessionId'] = base64_encode($this->encrypt($data['sessionId']));
        }

        if (is_null($this->targetUrl)) {
            throw new \DomainException("No target stored");
        }
        $url = $this->targetUrl;
        if (strpos($url, "?") !== FALSE) {
            $url .= "&";
        } else {
            $url .= "?";
        }
        $url .= "auth_resp=" . urlencode(base64_encode(json_encode($data)));
        return new RedirectResponse($url);
    }

    private function encrypt($value)
    {
        if (is_null($this->key)) {
            throw new \DomainException("No key stored");
        }
        $rawKey = base64_decode($this->key);

        // TODO: Maybe this could just be ($value^$rawKey)
        $out = '';
        for ($i = 0; $i < strlen($value); ++$i) {
            if ($i < strlen($rawKey)) {
                $out .= $rawKey[$i] ^ $value[$i];
            } else {
                $out .= $value[$i];
            }
        }
        return $out;
    }
}
