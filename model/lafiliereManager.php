<?php

class lafiliereManager{

	private $db;

	public function __construct(MyPDO $connect)
	{
		 $this->db=$connect;       
	}

   public function displayContentLafiliere(): array {
		$sql = "
		DESCRIBE
			lafiliere;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
	}

	
	public function filiereSelectAll (): array{
		
		$sql = "SELECT * FROM lafiliere  ;";
		$recup = $this->db->query($sql);

		if($recup->rowCount()===0){
			return [];
		}
			return $recup->fetchAll(PDO::FETCH_ASSOC);

	}

		
	public function filiereSelectById (int $idlafiliere): array{
		if(empty($idlafiliere)){
			return[];
		}

		$sql = "SELECT * FROM lafiliere WHERE idlafiliere = ? ;";
		$recup = $this->db->prepare($sql);

		$recup->bindValue(1,$idlafiliere,PDO::PARAM_INT);
		
		$recup->execute();

		if($recup->rowCount()===0){
			return [];
		}
		return $recup->fetch(PDO::FETCH_ASSOC);
	}




		
	public function filiereCreate (lafiliere $datas) {
		
		if(empty($datas->getlenom())||empty($datas->getlacronyme())){
					return false;
		}

		$sql = "INSERT INTO lafiliere (lenom, lacronyme, lacouleur, lepicto) VALUES (?,?,?,?);"; 

		$insert = $this->db->prepare($sql);

		$insert->bindValue(1,$datas->getLenom(),PDO::PARAM_STR);
		$insert->bindValue(2,$datas->getLacronyme(),PDO::PARAM_STR);
		$insert->bindValue(3,$datas->getLacouleur(),PDO::PARAM_STR);
		$insert->bindValue(4,$datas->getLepicto(),PDO::PARAM_STR);


		try {
			$insert->execute();
			return true;

		}catch(PDOException $e){
			echo $e->getCode();
			return false;

		}
	}




	public function filiereUpdate(lafiliere $datas, int $get){

		if(empty($datas->getlenom())||empty($datas->getlacronyme())||empty($datas->getidlafiliere())){

		}
		
		if($datas->getidlafiliere()!=$get){
			return false;

		}


	$sql = "UPDATE lafiliere SET lenom=?, lacronyme=? WHERE idlafiliere=?,";
	$update = $this->db->prepare($sql);

	$update->bindValue(1,$datas->getlenom(),PDO::PARAM_STR);
	$update->bindValue(2,$datas->getlacronyme(),PDO::PARAM_STR);
	$update->bindValue(3,$datas->getIdlafiliere(),PDO::PARAM_INT);

	try{
		$update->execute();
		return true;
	}catch (PDOException $e){
		echo $e->getCode();
		return false;
		
		}
	}




	public function filiereDelete(int $idlafiliere){
		
		$sql = "DELETE FROM lafiliere WHERE idlafiliere=?";
		
		$delete  = $this->db->prepare($sql);
		$delete->bindValue(1,$idlafiliere, PDO::PARAM_INT);
		
		try{
			$delete->execute();
			return true;
		}catch (PDOException $e){
			echo $e->getCode();
			return false;
		}
		
		
	}
		
		
		
}