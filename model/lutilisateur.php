<?php


class lutilisateur
{
protected $idlutilisateur;
protected $lenomutilisateur;
protected $lemotdepasse;
protected $lenom;
protected $leprenom;
protected $lemail;
protected $luniqueid;

//méthodes

public function __construct (array $data = [])
{

    if(!empty($data)){       
     $this->hydrate($data);
    }
}

protected function hydrate (array $tablehydrate ){
    foreach ($tablehydrate AS $key=>$value){
        $setterName = "set".ucfirst($key);
        if(method_exists($this,$setterName)){
            $this->$setterName($value);
        }
    }
}

    /**
     * GETTERS
     */
    public function getIdlutilisateur()
    {
        return htmlspecialchars_decode($this->idlutilisateur,ENT_QUOTES);
    }

    /**
     * @return mixed
     */
    public function getLenomutilisateur()
    {
        return $this->lenomutilisateur;
    }

    /**
     * @return mixed
     */
  public function getLemotdepasse()
    {
        return $this->lemotdepasse;
    }

    /**
     * @return mixed
     */
    public function getLenom()
    {
        return $this->lenom;
    }

    /**
     * @return mixed
     */
    public function getLeprenom()
    {
        return $this->leprenom;
    }

    /**
     * @return mixed
     */
    public function getLemail()
    {
        return $this->lemail;
    }

    /**
     * @return mixed
     */
    
    public function getLuniqueid()
    {
        return $this->luniqueid;
    }

    /**
     * SETTERS
     */
    public function setIdlutilisateur( int $idutilisateur)
    {
        if(!empty($idutilisateur)){

            $this->idlutilisateur = $idutilisateur;
        }
    }

    /**
     * @param mixed $lenomutilisateur
     */
    public function setLenomutilisateur (string $lenomutilisateur)
    {
		$patterns = [ 
			'/[ÀÄÂàäâ]/u',
			'/[ÉÈËÊéèëê]/u',
			'/[ÏÎïî]/u',
			'/[ÖÔöô]/u',
			'/[ÙÜÛùüû]/u',
			'/[Çç]/u',
			'/[Ææ]/u',
			'/[Œœ]/u',
		];
		$replacements = [
			'a',
			'e',
			'i',
			'o',
			'u',
			'c',
			'ae',
			'oe'
		];
		$nomFormatted = preg_replace($patterns, $replacements, strtolower($lenomutilisateur));
		if(strlen($nomFormatted) <= 80) {
			$this->lenomutilisateur = $nomFormatted;
		} else {
			echo "<h2 style='color: red;'>Cannot create because length sum of first name & last name exceeds 79 characters</h2>";
		}
    }


    public function setLemotdepasse($lemotdepasse)
    {
        $this->lemotdepasse = trim($lemotdepasse);
    }
	
	public function setLemotdepasseCrypte($lemotdepasse)
    {
        $this->lemotdepasse = password_hash((trim($lemotdepasse)), PASSWORD_DEFAULT);
    }



    public function setLenom( string $lenom)
    {
		if(strlen($lenom) <= 45) {
			$this->lenom = htmlspecialchars(strip_tags(trim($lenom)),ENT_QUOTES);
			$this->setLenomutilisateur($this->lenom);
		}
    }


    public function setLeprenom( string $leprenom)
    {
        $this->leprenom = htmlspecialchars(strip_tags(trim($leprenom)),ENT_QUOTES);
    }


    public function setLemail(string $lemail)
    {
		if(strlen($lemail) <= 180) {
			$this->lemail =htmlspecialchars(strip_tags(trim($lemail)),ENT_QUOTES);
		}
    }


    
    public function setLuniqueid( string $luniqueid='')
    {
        if(empty($luniqueid)){
            $this->luniqueid = uniqid('key',true);
        }else{
            $this->luniqueid = $luniqueid;
        }
    }



}


