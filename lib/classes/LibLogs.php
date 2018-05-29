<?

/**
CREATE TABLE `payment_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` datetime DEFAULT NULL,
  `price` float(11,2) DEFAULT NULL,
  `status_ans` char(255) DEFAULT NULL,
  `payed` enum('Y','N') DEFAULT 'N',
  `date_payed` datetime DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `phone` char(255) DEFAULT NULL,
  `email` char(255) DEFAULT NULL,
  `date_cancel` datetime DEFAULT NULL,
  `visite_date` date DEFAULT NULL,
  `visite_time` char(10) DEFAULT NULL,
  `visite_status` int(10) DEFAULT '0',
  `describe_system` char(255) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8
**/
class LibLogs{
	private $orderId=0;
	private $orderNumber='';
	private $sumOrder=0;
    function analiseRequest(){

    }
	static function save(){

		 if (isset($_REQUEST['nickname'])){
            $name=DB_string($_REQUEST['nickname']);
        }

		 if (isset($_REQUEST['email'])){
			$email=DB_string($_REQUEST['email']);	 
		 }

/*
		
		$ticket_id_p1=rand(1111111,99999999);
		$price=(float)$_REQUEST['amount'];
        $currency=$_REQUEST['currency'];
		$this->sumOrder=$price;
		$date_create=date('Y-m-d h:i:s');
		$describe_system=DB_string($_REQUEST['description']);

		$ins_sql="INSERT INTO `payment_order` (name,phone,email,price,currency,status_ans,payed,date_create,describe_system) VALUES ('$name','$phone','$email','$price','$currency','new','N','$date_create','$describe_system')";
		$rs=db_query($ins_sql);
		$this->orderId=db_last_id();
		$num=date('idmh');
		$numSave=$num.'-'.$this->orderId;
		$this->orderNumber=$numSave;
		$sqlGenNum="UPDATE `payment_order` SET order_num='".$numSave."' WHERE order_id=".$this->orderId;
		db_query($sqlGenNum);
		
		return $this->orderId;
		*/
	}


	function GetOrderRow($orderId){
		//$sql="SELECT * FROM `payment_order` WHERE order_id=".$orderId;
		//$rs=db_query($sql);
		//return db_fetch($rs);
	}

	function Update($orderId,$upFields)
	{
		/*$fiSet=array();
		foreach($upFields as $nameFields=>$valFields){
			$fiSet[]="`$nameFields`='$valFields'";
		}
		if ($orderId>0){
			$upd="Update payment_order SET ".implode(',',$fiSet)." WHERE order_id=$orderId";	
			$rs=db_query($upd);
			 
		}
		*/
		
	}
	function SetVisit($oder_id){
		//$oder_id=(int)$oder_id;
		//$upd="UPDATE  `payment_order` SET visite_status=1,visite_status_date=NOW() WHERE order_id=".$oder_id.' AND visite_status=0';
		//db_query($upd);
	}
	
}
?>