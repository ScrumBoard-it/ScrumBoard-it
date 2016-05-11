<?php
namespace ScrumBoardItBundle\Api;

use ScrumBoardItBundle\Exception\InvalidOptionException;
use ScrumBoardItBundle\Processor\ProcessorFactory;

/**
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraCallBuilder implements ApiCallBuilderInterface
{

    protected $apiConfiguration;

    protected $options;

    protected $result;

    public function __construct(array $options = array())
    {
        if (! empty($options)) {
            $this->setOptions($options);
        }
    }

    public function setApiConfiguration(ApiCallConfigurationInterface $apiConfiguration)
    {
        $this->apiConfiguration = $apiConfiguration;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getApiConfiguration()
    {
        return $this->apiConfiguration;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setResult($result)
    {
        $this->result = $result;
        
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function call()
    {
        if (empty($this->options['login']) || empty($this->options['password'])) {
            throw new InvalidOptionException('login or password');
        }
        $authorization = base64_encode($this->options['login'] . ':' . $this->options['password']);
        $curl = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $authorization
        );
        $config = $this->getApiConfiguration();
        $url = $this->options['host'] . $config->getUri();
        $params = $config->getParameters();
        switch ($config->getMethod()) {
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'GET':
            default:
                if (! empty($params)) {
                    $url .= '?' . http_build_query($params);
                }
                break;
        }
        // handle jira null pointer exception if no user agent
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $this->setResult(curl_exec($curl));
        curl_close($curl);
        
        return $this->process();
    }

    protected function process()
    {
        foreach ($this->getApiConfiguration()->getProcessors() as $name) {
            $factory = new ProcessorFactory();
            $factory->setContext($this);
            $processor = $factory->get($name);
            $processor->handle();
        }
        
        return $this->getResult();
    }

    /**
     * Recursive query builder
     * @param array $params
     * @return string
     */
    private function getQuery(array $params)
    {
        $query = '';
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subvalue) {
                    $query .= (!strlen($query) ? '?' : '&').http_build_query(array($key => $subvalue));
                }
            } else {
                $query .= (!strlen($query) ? '?' : '&').http_build_query(array($key => $value));
            }
        }
        return $query;
    }
}
