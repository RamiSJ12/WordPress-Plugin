<?php
require_once("../../../../../wp-load.php");
if(isset($_POST['movie-type'])){
	$type=$_POST['movie-type'];
	$title=$_POST['movie-title'];
	$listarr = array();
	if($type=="-") {
		echo "<div id='hideMe'>Please select list type and add some data first.</div>";
	}else{
		if(@$_POST['movie-val']!=""){
			$data = $_POST['movie-val'];
			$count = count($data);
			$movie = new shimdb_movie_grab();
			$listid = $movie->create_list_code();
			$icount = 0;
			 $page = 1 ;
			 $listarr['type'] = $type;
			 if($type=="title") {
				 foreach ( $data as $k => $d ) {
					 if ( $page == 2 ) {
						 $lastID = $wpdb->insert_id;
					 }
					 $value                                      = json_decode( base64_decode( $d ), true );
					 $listarr['content'][ $icount ]              = $value;
					 $listarr['content'][ $icount ]['num']       = ( $k + 1 ) . ". ";
					 $listarr['content'][ $icount ]['desc']      = $_POST['desc'][ $k ];
					 $listarr['content'][ $icount ]['list_desc'] = $_POST['listDesc'][ $k ];
					 if ( ( $icount + 1 ) == 100 or ( $k + 1 ) == $count ) {
						 $icount   = -1;
						 $arr      = base64_encode( json_encode( $listarr ) );
						 $listid   = $page == 1 ? $listid : $lastID . '-page';
						 $typeList = $page == 1 ? 'list' : "listpage";
						 $wpdb->query( 'INSERT INTO ' . $wpdb->prefix . 'shortcode_movie_cache (movie_id,title,type,cache,page) VALUES ("' . $listid . '","' . $title . '","' . $typeList . '","' . $arr . '",' . $page . ')' );
						 $page ++;
						 $listarr['content'] = array();
					 }
					 $icount ++;
				 }
			 }else if($type=="name"){
				 foreach ( $data as $k => $d ) {
					 if ( $page == 2 ) {
						 $lastID = $wpdb->insert_id;
					 }
					 $value                                      = json_decode( base64_decode( $d ), true );
					 $listarr['content'][ $icount ]              = $value;
					 $listarr['content'][$icount]['num']       = ( $k + 1 ) . ". ";
					 $listarr['content'][ $icount ]['bio']       =$_POST['desc'][ $k ];
					 $listarr['content'][ $icount ]['desc'] = $_POST['listDesc'][ $k ];
					 if ( ( $icount + 1 ) == 100 or ( $k + 1 ) == $count ) {
						 $icount   = -1;
						 $arr      = base64_encode( json_encode( $listarr ) );
						 $listid   = $page == 1 ? $listid : $lastID . '-page';
						 $typeList = $page == 1 ? 'list' : "listpage";
						 $wpdb->query( 'INSERT INTO ' . $wpdb->prefix . 'shortcode_movie_cache (movie_id,title,type,cache,page) VALUES ("' . $listid . '","' . $title . '","' . $typeList . '","' . $arr . '",' . $page . ')' );
						 $page ++;
						 $listarr['content'] = array();
					 }
					 $icount ++;
				 }
			 }



			echo "ok";

		}else{
			echo "<div id='hideMe'>You have to add some data.</div>";
		}

	}
}