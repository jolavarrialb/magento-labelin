<?php
/**
 * RateParcelsApi
 * PHP version 5
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Shipping API
 *
 * Shipping API Sample.
 *
 * The version of the OpenAPI document: 1.0.0
 * Contact: support@pb.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 4.3.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Labelin\PitneyBowesOfficialApi\Model\Shipping;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Labelin\PitneyBowesOfficialApi\Model\ApiException;
use Labelin\PitneyBowesOfficialApi\Model\Configuration;
use Labelin\PitneyBowesOfficialApi\Model\HeaderSelector;
use Labelin\PitneyBowesOfficialApi\Model\ObjectSerializer;

/**
 * RateParcelsApi Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class RateParcelsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $host_index (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null,
        $host_index = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $host_index;
    }

    /**
     * Set the host index
     *
     * @param  int Host index (required)
     */
    public function setHostIndex($host_index)
    {
        $this->hostIndex = $host_index;
    }

    /**
     * Get the host index
     *
     * @return Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation rateParcel
     *
     * Use this operation to rate a parcel before you print and purchase a shipment label. You can rate a parcel for multiple services and multiple parcel types with a single API request.
     *
     * @param  \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment $shipment Shipment request for Rates (required)
     * @param  bool $x_pb_unified_error_structure Set this to true to use the standard [error object](https://shipping.pitneybowes.com/reference/error-object.html#standard-error-object) if an error occurs. (optional, default to true)
     * @param  string $x_pb_shipper_rate_plan USPS Only. Shipper rate plan, if applicable. For more information, see [this FAQ](https://shipping.pitneybowes.com/faqs/rates.html#rate-plans-faq) (optional)
     * @param  string $x_pb_integrator_carrier_id USPS Only. Negotiated services rate, if applicable. (optional)
     * @param  string $x_pb_shipper_carrier_account_id UPS (United Parcel Service) Only. The unique identifier returned in the shipperCarrierAccountId field by the [Register an Existing Carrier Account](https://shipping.pitneybowes.com/api/post-carrier-accounts-register.html) API. (optional)
     * @param  bool $include_delivery_commitment When set to true, returns estimated transit time in days. (optional)
     * @param  string $carrier Cross-Border only. Required for PB Cross-Border. Set this to PBI. (optional)
     *
     * @throws \Labelin\PitneyBowesOfficialApi\Model\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment|\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Errors
     */
    public function rateParcel($shipment, $x_pb_unified_error_structure = true, $x_pb_shipper_rate_plan = null, $x_pb_integrator_carrier_id = null, $x_pb_shipper_carrier_account_id = null, $include_delivery_commitment = null, $carrier = null)
    {
        list($response) = $this->rateParcelWithHttpInfo($shipment, $x_pb_unified_error_structure, $x_pb_shipper_rate_plan, $x_pb_integrator_carrier_id, $x_pb_shipper_carrier_account_id, $include_delivery_commitment, $carrier);
        return $response;
    }

    /**
     * Operation rateParcelWithHttpInfo
     *
     * Use this operation to rate a parcel before you print and purchase a shipment label. You can rate a parcel for multiple services and multiple parcel types with a single API request.
     *
     * @param  \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment $shipment Shipment request for Rates (required)
     * @param  bool $x_pb_unified_error_structure Set this to true to use the standard [error object](https://shipping.pitneybowes.com/reference/error-object.html#standard-error-object) if an error occurs. (optional, default to true)
     * @param  string $x_pb_shipper_rate_plan USPS Only. Shipper rate plan, if applicable. For more information, see [this FAQ](https://shipping.pitneybowes.com/faqs/rates.html#rate-plans-faq) (optional)
     * @param  string $x_pb_integrator_carrier_id USPS Only. Negotiated services rate, if applicable. (optional)
     * @param  string $x_pb_shipper_carrier_account_id UPS (United Parcel Service) Only. The unique identifier returned in the shipperCarrierAccountId field by the [Register an Existing Carrier Account](https://shipping.pitneybowes.com/api/post-carrier-accounts-register.html) API. (optional)
     * @param  bool $include_delivery_commitment When set to true, returns estimated transit time in days. (optional)
     * @param  string $carrier Cross-Border only. Required for PB Cross-Border. Set this to PBI. (optional)
     *
     * @throws \Labelin\PitneyBowesOfficialApi\Model\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment|\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Errors, HTTP status code, HTTP response headers (array of strings)
     */
    public function rateParcelWithHttpInfo($shipment, $x_pb_unified_error_structure = true, $x_pb_shipper_rate_plan = null, $x_pb_integrator_carrier_id = null, $x_pb_shipper_carrier_account_id = null, $include_delivery_commitment = null, $carrier = null)
    {
        $request = $this->rateParcelRequest($shipment, $x_pb_unified_error_structure, $x_pb_shipper_rate_plan, $x_pb_integrator_carrier_id, $x_pb_shipper_carrier_account_id, $include_delivery_commitment, $carrier);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            switch($statusCode) {
                case 200:
                    if ('\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment' === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = (string) $responseBody;
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                default:
                    if ('\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Errors' === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = (string) $responseBody;
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Errors', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment';
            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = (string) $responseBody;
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Errors',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation rateParcelAsync
     *
     * Use this operation to rate a parcel before you print and purchase a shipment label. You can rate a parcel for multiple services and multiple parcel types with a single API request.
     *
     * @param  \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment $shipment Shipment request for Rates (required)
     * @param  bool $x_pb_unified_error_structure Set this to true to use the standard [error object](https://shipping.pitneybowes.com/reference/error-object.html#standard-error-object) if an error occurs. (optional, default to true)
     * @param  string $x_pb_shipper_rate_plan USPS Only. Shipper rate plan, if applicable. For more information, see [this FAQ](https://shipping.pitneybowes.com/faqs/rates.html#rate-plans-faq) (optional)
     * @param  string $x_pb_integrator_carrier_id USPS Only. Negotiated services rate, if applicable. (optional)
     * @param  string $x_pb_shipper_carrier_account_id UPS (United Parcel Service) Only. The unique identifier returned in the shipperCarrierAccountId field by the [Register an Existing Carrier Account](https://shipping.pitneybowes.com/api/post-carrier-accounts-register.html) API. (optional)
     * @param  bool $include_delivery_commitment When set to true, returns estimated transit time in days. (optional)
     * @param  string $carrier Cross-Border only. Required for PB Cross-Border. Set this to PBI. (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function rateParcelAsync($shipment, $x_pb_unified_error_structure = true, $x_pb_shipper_rate_plan = null, $x_pb_integrator_carrier_id = null, $x_pb_shipper_carrier_account_id = null, $include_delivery_commitment = null, $carrier = null)
    {
        return $this->rateParcelAsyncWithHttpInfo($shipment, $x_pb_unified_error_structure, $x_pb_shipper_rate_plan, $x_pb_integrator_carrier_id, $x_pb_shipper_carrier_account_id, $include_delivery_commitment, $carrier)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation rateParcelAsyncWithHttpInfo
     *
     * Use this operation to rate a parcel before you print and purchase a shipment label. You can rate a parcel for multiple services and multiple parcel types with a single API request.
     *
     * @param  \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment $shipment Shipment request for Rates (required)
     * @param  bool $x_pb_unified_error_structure Set this to true to use the standard [error object](https://shipping.pitneybowes.com/reference/error-object.html#standard-error-object) if an error occurs. (optional, default to true)
     * @param  string $x_pb_shipper_rate_plan USPS Only. Shipper rate plan, if applicable. For more information, see [this FAQ](https://shipping.pitneybowes.com/faqs/rates.html#rate-plans-faq) (optional)
     * @param  string $x_pb_integrator_carrier_id USPS Only. Negotiated services rate, if applicable. (optional)
     * @param  string $x_pb_shipper_carrier_account_id UPS (United Parcel Service) Only. The unique identifier returned in the shipperCarrierAccountId field by the [Register an Existing Carrier Account](https://shipping.pitneybowes.com/api/post-carrier-accounts-register.html) API. (optional)
     * @param  bool $include_delivery_commitment When set to true, returns estimated transit time in days. (optional)
     * @param  string $carrier Cross-Border only. Required for PB Cross-Border. Set this to PBI. (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function rateParcelAsyncWithHttpInfo($shipment, $x_pb_unified_error_structure = true, $x_pb_shipper_rate_plan = null, $x_pb_integrator_carrier_id = null, $x_pb_shipper_carrier_account_id = null, $include_delivery_commitment = null, $carrier = null)
    {
        $returnType = '\Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment';
        $request = $this->rateParcelRequest($shipment, $x_pb_unified_error_structure, $x_pb_shipper_rate_plan, $x_pb_integrator_carrier_id, $x_pb_shipper_carrier_account_id, $include_delivery_commitment, $carrier);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = (string) $responseBody;
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'rateParcel'
     *
     * @param  \Labelin\PitneyBowesOfficialApi\Model\shippingApi\model\Shipment $shipment Shipment request for Rates (required)
     * @param  bool $x_pb_unified_error_structure Set this to true to use the standard [error object](https://shipping.pitneybowes.com/reference/error-object.html#standard-error-object) if an error occurs. (optional, default to true)
     * @param  string $x_pb_shipper_rate_plan USPS Only. Shipper rate plan, if applicable. For more information, see [this FAQ](https://shipping.pitneybowes.com/faqs/rates.html#rate-plans-faq) (optional)
     * @param  string $x_pb_integrator_carrier_id USPS Only. Negotiated services rate, if applicable. (optional)
     * @param  string $x_pb_shipper_carrier_account_id UPS (United Parcel Service) Only. The unique identifier returned in the shipperCarrierAccountId field by the [Register an Existing Carrier Account](https://shipping.pitneybowes.com/api/post-carrier-accounts-register.html) API. (optional)
     * @param  bool $include_delivery_commitment When set to true, returns estimated transit time in days. (optional)
     * @param  string $carrier Cross-Border only. Required for PB Cross-Border. Set this to PBI. (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function rateParcelRequest($shipment, $x_pb_unified_error_structure = true, $x_pb_shipper_rate_plan = null, $x_pb_integrator_carrier_id = null, $x_pb_shipper_carrier_account_id = null, $include_delivery_commitment = null, $carrier = null)
    {
        // verify the required parameter 'shipment' is set
        if ($shipment === null || (is_array($shipment) && count($shipment) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $shipment when calling rateParcel'
            );
        }

        $resourcePath = '/v1/rates';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        if ($include_delivery_commitment !== null) {
            if('form' === 'form' && is_array($include_delivery_commitment)) {
                foreach($include_delivery_commitment as $key => $value) {
                    $queryParams[$key] = $value;
                }
            }
            else {
                $queryParams['includeDeliveryCommitment'] = $include_delivery_commitment;
            }
        }
        // query params
        if ($carrier !== null) {
            if('form' === 'form' && is_array($carrier)) {
                foreach($carrier as $key => $value) {
                    $queryParams[$key] = $value;
                }
            }
            else {
                $queryParams['carrier'] = $carrier;
            }
        }

        // header params
        if ($x_pb_unified_error_structure !== null) {
            $headerParams['X-PB-UnifiedErrorStructure'] = ObjectSerializer::toHeaderValue($x_pb_unified_error_structure);
        }
        // header params
        if ($x_pb_shipper_rate_plan !== null) {
            $headerParams['X-PB-Shipper-Rate-Plan'] = ObjectSerializer::toHeaderValue($x_pb_shipper_rate_plan);
        }
        // header params
        if ($x_pb_integrator_carrier_id !== null) {
            $headerParams['X-PB-Integrator-CarrierId'] = ObjectSerializer::toHeaderValue($x_pb_integrator_carrier_id);
        }
        // header params
        if ($x_pb_shipper_carrier_account_id !== null) {
            $headerParams['X-PB-Shipper-Carrier-AccountId'] = ObjectSerializer::toHeaderValue($x_pb_shipper_carrier_account_id);
        }


        // body params
        $_tempBody = null;
        if (isset($shipment)) {
            $_tempBody = $shipment;
        }

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json'],
                ['application/json']
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            if ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode(ObjectSerializer::sanitizeForSerialization($_tempBody));
            } else {
                $httpBody = $_tempBody;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return new Request(
            'POST',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}