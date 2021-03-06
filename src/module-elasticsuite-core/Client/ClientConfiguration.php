<?php
/**
 * DISCLAIMER :
 *
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile_Elasticsuite
 * @package   Smile\ElasticsuiteCore
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\ElasticsuiteCore\Client;

use Smile\ElasticsuiteCore\Api\Client\ClientConfigurationInterface;

/**
 * ElasticSearch client configuration implementation.
 *
 * @category  Smile
 * @package   Smile\ElasticsuiteCore
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class ClientConfiguration implements ClientConfigurationInterface
{
    /**
     * Location of Elasticsearch client configuration.
     *
     * @var string
     */
    const ES_CLIENT_CONFIG_XML_PREFIX = 'smile_elasticsuite_core_base_settings/es_client';

    /**
     * @var array
     */
    private $options;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig Config.
     * @param array                                              $options     Custom options.
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        $options = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->options     = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getServerList()
    {
        return explode(',', $this->getElasticsearchClientConfigParam('servers'));
    }

    /**
     * {@inheritdoc}
     */
    public function isDebugModeEnabled()
    {
        return (bool) $this->getElasticsearchClientConfigParam('enable_debug_mode');
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionTimeout()
    {
        return (int) $this->getElasticsearchClientConfigParam('connection_timeout');
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme()
    {
        return (bool) $this->getElasticsearchClientConfigParam('enable_https_mode') ? 'https' : 'http';
    }

    /**
     * {@inheritdoc}
     */
    public function isHttpAuthEnabled()
    {
        $authEnabled = (bool) $this->getElasticsearchClientConfigParam('enable_http_auth');

        return $authEnabled && !empty($this->getHttpAuthUser()) && !empty($this->getHttpAuthPassword());
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpAuthUser()
    {
        return (string) $this->getElasticsearchClientConfigParam('http_auth_user');
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpAuthPassword()
    {
        return (string) $this->getElasticsearchClientConfigParam('http_auth_pwd');
    }

    /**
     * Read config under the path smile_elasticsuite_core_base_settings/es_client.
     *
     * @param string $configField Field name.
     *
     * @return mixed
     */
    private function getElasticsearchClientConfigParam($configField)
    {
        $path = self::ES_CLIENT_CONFIG_XML_PREFIX . '/' . $configField;

        return $this->options[$configField] ?? $this->scopeConfig->getValue($path);
    }
}
