<? php 

espaço  para nome Hcode \ Model ;

use \ Hcode \ DB \ Sql ;
use \ Hcode \ Model ;

classe  Endereço  estende o  Modelo {

	const  SESSION_ERROR = "AddressError" ;

	 função estática  pública getCEP ( $ nrcep ) 
	{

		$ nrcep = str_replace ( "-" , "" , $ nrcep );

		$ ch = curl_init ();

		curl_setopt ( $ ch , CURLOPT_URL , "http://viacep.com.br/ws/$nrcep/json/" );

		curl_setopt ( $ ch , CURLOPT_RETURNTRANSFER , verdadeiro );
		curl_setopt ( $ ch , CURLOPT_SSL_VERIFYPEER , false );

		$ data = json_decode ( curl_exec ( $ ch ), verdadeiro );

		curl_close ( $ ch );

		retornar  $ dados ;

	}

	 função  pública loadFromCEP ( $ nrcep )
	{

		$ data = Endereço :: getCEP ( $ nrcep );

		if ( isset ( $ data [ 'logradouro' ]) && $ data [ 'logradouro' ]) {

			$ this -> setdesaddress ( $ data [ 'logradouro' ]);
			$ this -> setdescomplement ( $ data [ 'complemento' ]);
			$ this -> setdesdistrict ( $ data [ 'bairro' ]);
			$ this -> setdescity ( $ data [ 'localidade' ]);
			$ this -> setdesstate ( $ data [ 'uf' ]);
			$ this -> setdescountry ( 'Brasil' );
			$ this -> setdeszipcode ( $ nrcep );

		}

	}

	 função  pública save ()
	{

		$ sql = novo  Sql ();

		$ results = $ sql -> select ( "CALL sp_addresses_save (: idaddress,: idperson,: desaddress,: desnumber,: descomplement,: descity,: desstate,: descountry,: deszipcode,: desdistrict)" , [
			': idaddress' => $ this -> getidaddress (),
			': idperson' => $ this -> getidperson (),
			': desaddress' => utf8_decode ( $ this -> getdesaddress ()),
			': desnumber' => $ this -> getdesnumber (),
			': descomplement' => utf8_decode ( $ this -> getdescomplement ()),
			': descity' => utf8_decode ( $ this -> getdescity ()),
			': desstate' => utf8_decode ( $ this -> getdesstate ()),
			': descountry' => utf8_decode ( $ this -> getdescountry ()),
			': deszipcode' => $ this -> getdeszipcode (),
			': desdistrict' => $ this -> getdesdistrict ()
		]);

		if ( count ( $ results )> 0 ) {
			$ this -> setData ( $ results [ 0 ]);
		}

	}

	 função estática  pública setMsgError ( $ msg ) 
	{

		$ _SESSION [ Endereço :: SESSION_ERROR ] = $ msg ;

	}

	 função estática  pública getMsgError () 
	{

		$ msg = ( isset ( $ _SESSION [ Endereço :: SESSION_ERROR ]))? $ _SESSION [ Endereço :: SESSION_ERROR ]: "" ;

		Endereço :: clearMsgError ();

		retornar  $ msg ;

	}

	 função estática  pública clearMsgError () 
	{

		$ _SESSION [ Endereço :: SESSION_ERROR ] = NULL ;

	}

}

 ?>