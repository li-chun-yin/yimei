<?php
namespace  library\douyin;

use asbamboo\http\Stream;
use asbamboo\http\StreamInterface;

class MultipartStream extends Stream implements StreamInterface
{   
    /**
     * stream类型的resource
     *
     * @var resource
     */
    protected $resource;
    
    protected $resource_stag;
    
    protected $resource_etag;
    
    public function __construct(string $resource_path, string $boundary, string $name, string $filename, string $content_type)
    {
        $this->resource_stag    = new Stream('php://temp', 'w+b');
        $this->resource_stag->write(implode("\r\n", [
            "--{$boundary}",
            "Content-Type: {$content_type}",
            "Content-Disposition: form-data; name=\"{$name}\"; filename=\"{$filename}\"\r\n\r\n",
        ]));
        
        $this->resource         = new Stream($resource_path, 'rb');
        
        $this->resource_etag    = new Stream('php://temp', 'w+b');
        $this->resource_etag->write("\r\n--{$boundary}--");
        
    }
    
    
    /**
     * 
     * {@inheritDoc}
     * @see \Psr\Http\Message\StreamInterface::close()
     */
    public function close() : void
    {
        $this->resource_etag->detach();
        $this->resource->detach();
        $this->resource_stag->detach();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Psr\Http\Message\StreamInterface::detach()
     */
    public function detach()
    {
        $this->resource_etag->detach();
        $this->resource->detach();
        $this->resource_stag->detach();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Psr\Http\Message\StreamInterface::getSize()
     */
    public function getSize() : ?int
    {
        return $this->resource_stag->getSize() +  $this->resource->getSize() +  $this->resource_etag->getSize();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Psr\Http\Message\StreamInterface::read()
     */
    public function read($length) : string
    {
        $content    = '';
        if(!$this->resource_stag->eof()){    
            $content        .= $this->resource_stag->read($length);
            $readed_length  = strlen($content); 
            if($length > $readed_length){
                $length     = $length - $readed_length;
            }
        }
        if($length > 0 && !$this->resource->eof()){
            $content        .= $this->resource->read($length);
            $readed_length  = strlen($content);
            if($length > $readed_length){
                $length     = $length - $readed_length;
            }
        }
        if($length > 0 && !$this->resource_etag->eof()){
            $content        .= $this->resource_etag->read($length);
        }
        return $content;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\http\psr\Stream::isSeekable()
     */
    public function isSeekable() : bool
    {
        return true;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see StreamInterface::getContents()
     */
    public function getContents() : string
    {
        $contents = $this->resource_stag->getContents() . $this->resource->getContents() . $this->resource_etag->getContents();
        $this->resource_stag->rewind();
        $this->resource->rewind();
        $this->resource_etag->rewind();
        return $contents;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\http\psr\Stream::tell()
     */
    public function tell(): int
    {
        if(!$this->resource_stag->eof()){
            return $this->resource_stag->tell();
        }

        if(!$this->resource->eof()){
            return $this->resource->tell() + $this->resource_stag->getSize();
        }

        if(!$this->resource_etag->eof()){
            return $this->resource_etag->tell() + $this->resource_stag->getSize() + $this->resource->getSize();
        }
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\http\psr\Stream::rewind()
     */
    public function rewind() : bool
    {
        return $this->resource_stag->rewind() && $this->resource->rewind() && $this->resource_etag->rewind();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\http\psr\Stream::getMetadata()
     */
    public function getMetadata(/*string*/ $key = null)
    {
        return $this->resource->getMetadata($key);
    }
}