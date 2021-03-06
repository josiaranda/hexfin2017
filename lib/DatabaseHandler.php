<?php 
/**
* 
*/
class DatabaseHandler 
{
	
	function __construct()
	{
		$this->servername = "35.194.159.60";
		$this->username = "hexfin";
		$this->password = "hexfin";
		$this->dbname='hexafin';
		require_once 'ApiHandler.php';
		// Create connection
		
		
	}

	public function openDB()
	{
		try {
		    $this->conn = new PDO("mysql:host=$this->servername;dbname=hexafin", $this->username, $this->password);
		    // set the PDO error mode to exception
		    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		     
		    }
		catch(PDOException $e)
		    {
		    print_r($e);
		    }
	}
	public function closeDB()
	{
		$this->conn=null;
	}

	public function SignUp($data)
	{
		$sql="insert into Users values('$data[CustomerName]','$data[DateOfBirth]','$data[MobileNumber]','$data[EmailAddress]','$data[IDNumber]','$data[Password]')";
		try {
			$this->openDB();
			$this->conn->exec($sql);
			$this->closeDB();
			return array("status"=>'success','response'=>'');
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function LogIn($data)
	{
		$sql="select CustomerName,EmailAddress from Users where EmailAddress='$data[EmailAddress]' and Password='$data[Password]'";
		try {
			$this->openDB();
			$stmt=$this->conn->prepare($sql);
			$stmt->execute();
			$response=$stmt->setFetchMode(PDO::FETCH_ASSOC); 
			$this->closeDB();
			return array("status"=>'success','response'=>$stmt->fetchAll());
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function MakeWallet($data)
	{
		$sql="select * from Users where EmailAddress='$data[EmailAddress]' ";
		try {
			$this->openDB();
			$stmt=$this->conn->prepare($sql);
			$stmt->execute();
			$response=$stmt->setFetchMode(PDO::FETCH_ASSOC); 
			$result=$stmt->fetchAll();
			
			$sql="insert into Wallet(Type,EmailAddress,Name) values('reguler','$data[EmailAddress]','$data[Name]')";
			$this->conn->exec($sql);
			$result[0]['PrimaryID']=$this->conn->lastInsertId();
			$this->closeDB();
			$ApiHandler=new ApiHandler();
			$response=$ApiHandler->WalletReg($result[0]);

			return array("status"=>'success','response'=>$response);
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function MakeDeposit($data)
	{
		# code...
	}

	public function TopUp($data)
	{
		$data['RequestDate']=date('Y-m-d').'T'.date('H:i:s.000P');
		try {
			$this->openDB();
			$sql="insert into Transaction(TransactionType,ISODate,Amount,PrimaryID) values('topup','$data[RequestDate]','$data[Amount]',$data[PrimaryID])";
			$this->conn->exec($sql);
			$data['TransactionID']=$this->conn->lastInsertId();
			$this->closeDB();
			$ApiHandler= new ApiHandler();
			$response=$ApiHandler->TopUp($data);
			$this->openDB();
			$sql="insert into TopUp_Detail(TransactionID,BCAReferenceID) values($data[TransactionID],'$response[response]')";
			$this->conn->exec($sql);
			$this->closeDB();
			return array("status"=>'success','response'=>$response);
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function getOTP($data)
	{
		$data['RequestDate']=date('Y-m-d').'T'.date('H:i:s.000P');
		try {
			$this->openDB();
			$sql="insert into Transaction(TransactionType,ISODate,Amount,PrimaryID) values('$data[Type]','$data[RequestDate]','$data[Amount]',$data[PrimaryID])";
			$this->conn->exec($sql);
			$data['TransactionID']=$this->conn->lastInsertId();
			$this->closeDB();
			$ApiHandler= new ApiHandler();
			$response=$ApiHandler->getOTP($data);
			$response=$response['response'];
			$otp=$response->getOTPCode();
			$exp=$response->getExpiredDate();
			$this->openDB();
			$sql="insert into OTP_Detail(OTPCode,ExpiredDate,TransactionID) values('$otp','$exp',$data[TransactionID])";
			$this->conn->exec($sql);
			$this->closeDB();
			return array("status"=>'success','response'=>$otp);
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function Transfer($data,$RecipientID)
	{
		$data['RequestDate']=date('Y-m-d').'T'.date('H:i:s.000P');
		try {
			$this->openDB();
			$sql="insert into Transaction(TransactionType,ISODate,Amount,PrimaryID) values('withdraw','$data[RequestDate]','$data[Amount]',$data[PrimaryID])";
			$this->conn->exec($sql);
			$data['TransactionID']=$this->conn->lastInsertId();
			$this->closeDB();
			$ApiHandler= new ApiHandler();
			$response=$ApiHandler->Withdraw($data);
			$this->openDB();
			$sql="insert into Transaction(TransactionType,ISODate,Amount,PrimaryID) values('topup','$data[RequestDate]','$data[Amount]',$RecipientID)";
			$this->conn->exec($sql);
			$data['TransactionID']=$this->conn->lastInsertId();
			$this->closeDB();
			$ApiHandler= new ApiHandler();
			$data['PrimaryID']=$RecipientID;
			$response=$ApiHandler->TopUp($data);
			$this->openDB();
			$sql="insert into TopUp_Detail(TransactionID,BCAReferenceID) values($data[TransactionID],'$response[response]')";
			$this->conn->exec($sql);
			$this->closeDB();
			return array("status"=>'success','response'=>$response);
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function getWallets($EmailAddress)
	{
		$sql="select * from Wallet where EmailAddress='$EmailAddress' ";
		try {
			$this->openDB();
			$stmt=$this->conn->prepare($sql);
			$stmt->execute();
			$response=$stmt->setFetchMode(PDO::FETCH_ASSOC); 
			$result=$stmt->fetchAll();
			
			$this->closeDB();
			
			return array("status"=>'success','response'=>$result);
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}

	public function OTPLists($EmailAddress)
	{
		$sql="SELECT ot.*,  t.`Amount`,  w.`Name`, t.`TransactionType` FROM  `OTP_Detail` ot   JOIN `Transaction` t     ON ot.`TransactionID` = t.`TransactionID`   JOIN Wallet   w   ON w.`PrimaryID` = t.`PrimaryID`     JOIN `Users` u ON u.`EmailAddress`=w.`EmailAddress`    WHERE w.`EmailAddress`='$EmailAddress' ";
		try {
			$this->openDB();
			$stmt=$this->conn->prepare($sql);
			$stmt->execute();
			$response=$stmt->setFetchMode(PDO::FETCH_ASSOC); 
			$result=$stmt->fetchAll();
			
			$this->closeDB();
			
			return array("status"=>'success','response'=>$result);
		} catch (Exception $e) {
			return array('status'=>'failed','response'=>$e);
		}
	}
}

 ?>