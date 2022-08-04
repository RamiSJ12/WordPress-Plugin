$listarr['type'] = 'name';
$i=1;
$NameList = json_decode($this->decrypt($ext_dom_key['NameList'], $key));
$firstarr= array();

while($i<=$howmanypage){
	if($i == 2){
		$lastID = $wpdb->insert_id;
	}
	$listarr['content'] = array();
	if($i>1){
		$pathx = $this->list_dom($id,$i);
		$xpath = $pathx['xpath'];
	}
	@$nameItem = $xpath->query($NameList->nameItem);
	if(@$nameItem->length>0){
		foreach ($nameItem as $k => $item){
			$xp = $this->html_dom($item,$pathx['dom']);
			@$photo = $xp->query($NameList->photo);
			if(@$photo->length>0){
				$listarr['content'][$k]['photo'] = $photo->item(0)->nodeValue;
			}else{
				$listarr['content'][$k]['photo'] = "";
			}
			@$nameMOVIE = $xp->query($NameList->nameMOVIE);
			if(@$nameMOVIE->length>0){
				$name_link = $nameMOVIE->item(0)->getAttribute('href');
				$listarr['content'][$k]['name_link'] = 'https://ramisj.dev'.$name_link;
				$listarr['content'][$k]['name'] = '<a href="'" target="_blank">'.mb_convert_encoding(utf8_decode($nameMOVIE->item(0)->nodeValue), 'HTML-ENTITIES', 'UTF-8').'</a>';
			}else{
				$listarr['content'][$k]['name_link'] = "";
				$listarr['content'][$k]['name'] = "";
			}

			@$numItem = $xp->query($NameList->numItem);
			if(@$numItem->length>0){
				$listarr['content'][$k]['num'] = $numItem->item(0)->nodeValue;
			}else{
				$listarr['content'][$k]['num'] = "";
			}
			@$genderAc = $xp->query($NameList->genderAc);
			if(@$genderAc->length>0){
				$gender = explode('|',$genderAc->item(0)->nodeValue);
				$gender = trim($gender[0]);
				$listarr['content'][$k]['gender']  = $gender;
			}else{
				$listarr['content'][$k]['gender'] = "";
			}
			@$bestTitle = $xp->query($NameList->bestTitle);
			if(@$bestTitle->length>0){
				$bestT = mb_convert_encoding(utf8_decode($bestTitle->item(0)->nodeValue), 'HTML-ENTITIES', 'UTF-8');
				@$bestTLink =  $bestTitle->item(0)->getAttribute('href');
				$listarr['content'][$k]['bestTitle'] = '<a href="" target="_blank">'.$bestT.'</a>';
			}else{
				$listarr['content'][$k]['bestTitle'] = "";
			}

			@$bio = $xp->query($NameList->bio);
			if(@$bio->length>0){
				$listarr['content'][$k]['bio'] =  mb_convert_encoding(utf8_decode($bio->item(1)->nodeValue), 'HTML-ENTITIES', 'UTF-8');
			}else{
				$listarr['content'][$k]['bio'] = "";
			}

			@$listDesc = $xp->query($NameList->listDesc);
			if(@$listDesc->length>0){
				$listarr['content'][$k]['desc'] =  mb_convert_encoding(utf8_decode($listDesc->item(0)->nodeValue), 'HTML-ENTITIES', 'UTF-8');
			}else{
				$listarr['content'][$k]['desc'] = "";
			}


		}

	}

	if($i==1){
		$firstarr = $listarr;
	}
	$arr = base64_encode( json_encode( $listarr ) );
	$typeList  = $i==1 ? 'list' : "listpage";
	$listID  = $i==1 ? $id : $lastID.'-page';
	$wpdb->query( 'INSERT INTO ' . $wpdb->prefix . 'shortcode_movie_cache (movie_id,title,type,cache,page) VALUES ("' . $listID . '","' . $Title . '","'.$typeList.'","' . $arr . '",'.$i.')' );

	$i++;
}
$listarr = $firstarr;
header("location: " . $_SERVER['REQUEST_URI']);
