<?php

class lafiliere
{
protected $idlafiliere;
protected $lenom;
protected $lacronyme;
protected $lacouleur;
protected $lepicto;
protected $actif=1;


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
    public function getIdlafiliere()
    {
        return htmlspecialchars_decode($this->idlafiliere,ENT_QUOTES);
    }

    /**
     * @return mixed
     */
    public function getLenom()
    {
        return htmlspecialchars_decode($this->lenom,ENT_QUOTES);
    }

    /**
     * @return mixed
     */
  public function getLacronyme()
    {
        return htmlspecialchars_decode($this->lacronyme,ENT_QUOTES);
    }

     /**
     * @return mixed
     */
  public function getLacouleur()
  {
      return $this->lacouleur;
  }

   /**
     * @return mixed
     */
    public function getLepicto()
    {
        return $this->lepicto;
    }
	
	public function getActif()
    {
        return $this->actif;
    }



   

    /**
     * SETTERS
     */
    public function setIdlafiliere( int $idlafiliere)
    {
        if(!empty($idlafiliere)){

            $this->idlafiliere = $idlafiliere;
        }
    }

    /**
     * @param mixed $lenom
     */
    public function setLenom (string $lenom)
    {
        $this->lenom =htmlspecialchars(strip_tags(trim($lenom)),ENT_QUOTES);
    }

    /**
     * @param mixed $lacronyme
     */
    public function setLacronyme($lacronyme)
    {
        $this->lacronyme =htmlspecialchars(strip_tags(trim($lacronyme)),ENT_QUOTES);
    }

     /**
     * @param mixed $lacouleur
     */
    public function setLacouleur($lacouleur)
    {
        $this->lacouleur =htmlspecialchars(strip_tags(trim($lacouleur)),ENT_QUOTES);
    }


     /**
     * @param mixed $lepicto
     */
    public function setLepicto($lepicto)
    {
        $this->lepicto =htmlspecialchars(strip_tags(trim($lepicto)),ENT_QUOTES);
    }

    public function setActif(int $actif)
    {
            $this->actif = $actif;
    }
	
}
