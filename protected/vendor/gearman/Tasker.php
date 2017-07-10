<?php
class PhpGearman
{
	private $sock;
	private $server;
	private $port;
	
	private $name;
	private $data;
	
	private function connect()
	{
		$errorNumber = $errorString = null;
		$this->sock = @fsockopen($this->server, $this->port, $errorNumber, $errorString);
		return ($errorNumber == 0 && $errorString == '');
	}
	
	public function addServer($server = '127.0.0.1', $port = 4730)
	{
		$this->server = $server;
		$this->port = $port;
		return $this->connect();
	}
	
	public function addTaskBackground($name, $data)
	{
		$this->name = $name;
		$this->data = $data;
	}
	
	public function runTasks()
	{
		if ($this->sock === null) $this->connect();
		
		$job = array($this->name, null, $this->data);
		$job = join("\0", $job);
		$code = "\0REQ";
		$header = pack('NN', 18, strlen($job));
		$task   = $code . $header . $job;
		
		try
		{
			fwrite($this->sock, $task);
		}
		catch(Exception $ex)
		{
			return false;
		}
		
		return true;
	}
}


class Tasker extends CComponent
{
	public $server = '127.0.0.1';
	public $port = 4730;
	public $prefix = '';
	
	public $client;
	
	public function init()
	{
		if (extension_loaded('gearman'))
		{
			$this->client = new GearmanClient();
		}
		else
		{
			$this->client = new PhpGearman();
		}
		
		if (!$this->client->addServer($this->server, $this->port))
		{
			throw new CException('Tasker connect error!');
		}
	}
	
	public function submitJob($name, $data)
	{
		$this->client->addTaskBackground($this->prefix . $name, $data);
		$this->client->runTasks();
	}
}
?>
