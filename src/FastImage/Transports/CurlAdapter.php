<?php namespace FastImage\Transports;

/**
 * Class CurlAdapter
 *
 * Not as fast since we just get the first 32768 bytes regardless
 * of image, but a little bit more stable
 *
 * @package FastImage\Transports
 * @author  Will Washburn <will@willwashburn.com>
 */
class CurlAdapter implements TransportInterface {

    /**
     * The curl handle
     * @var resource
     */
    protected $handle;

    /**
     * @var int
     */
    protected $strpos = 0;

    /**
     * The string from the file
     *
     * @var string
     */
    protected $str = '';

    /**
     * The bits from the image
     *
     * @var string
     */
    protected $data;

    /**
     * @var int
     */
    protected $timeout = 10;

    /**
     * Opens the connection to the file
     *
     * @param $url
     *
     * @throws Exception
     *
     * @return $this;
     */
    public function open($url)
    {
        $headers = array(
            "Range: bytes=0-32768"
        );

        $this->handle = curl_init($url);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
        $data = curl_exec($this->handle);

        if (curl_errno($this->handle)) {
            throw new Exception(curl_error($this->handle), curl_errno($this->handle));
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Closes the connection to the file
     *
     * @return $this
     */
    public function close()
    {
        curl_close($this->handle);
        return $this;
    }

    /**
     * Reads more characters of the file
     *
     * @param $characters
     *
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function read($characters)
    {
        if (!is_numeric($characters)) {
            throw new \InvalidArgumentException('"Read" expects a number');
        }

        $response = null;

        $result = substr($this->data, $this->strpos, $characters);
        $this->strpos += $characters;

        return $result;
    }

    /**
     * Resets the pointer where we are reading in the file
     *
     * @return mixed
     */
    public function resetReadPointer()
    {
        $this->strpos = 0;
    }

    /**
     * @param $seconds
     *
     * @return $this
     */
    public function setTimeout($seconds)
    {
        $this->timeout = floatval($seconds);

        return $this;
    }

}