<?php
function sdztc_sanitize( $string = '' ) {
	$charactes = [
		'Š' =>'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
		'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
		'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
		'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y',
		
		' ' => '-', '.' => '', ':' => '',
	];

	$string = str_replace( 
		array_keys( $charactes ),
		array_values( $charactes ),
		$string
	);

	$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
	return strtolower( trim( $string ) );
	
	return sanitize_title( $string );
}

function sdztc_modifyCode( $html ) {
	$doc = new DomDocument;
	libxml_use_internal_errors(true);
	$html = @$doc::loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	
	foreach( [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ] as $headerTag ) {
		foreach( $html->getElementsByTagName( $headerTag ) as $header ) {
			$id = $header->getAttribute( 'id' );
			if( empty( $id ) ) {
				// $id = sdztc_sanitize( $header->textContent );
				$id = sanitize_title( $header->textContent );
				$header->setAttribute( 'id', $id );
			}
		}
	}
	
	$html = preg_replace( '/^(<\?xml encoding="utf-8" \?>)/m', '', $html->saveHTML() );
	
	return $html;
}

function sdztc_getHeaders( $html ) {
	$doc = new DomDocument;
	$html = @$doc::loadHTML( $html );
	$xpath = new DOMXPath( $html );
	
	$headers = $xpath->query( '//h1|//h2|//h3|//h4|//h5|//h6' );
	$containTable = [];
	$pos = [];
	
	for( $i = 0; $i < sizeof( $headers ); $i ++ ) {
		$header = $headers[ $i ];
		
		switch( $header->nodeName ) {
			case 'h1':
				$containTable[] = [
					'value' => $header->nodeValue,
					'id' => $header->getAttribute( 'id' ),
					'childs' => [],
				];
				$pos[ 1 ] = sizeof( $containTable ) - 1;
				$pos[ 2 ] = 0;
				break;
				
			case 'h2':
				$containTable[ $pos[ 1 ] ][ 'childs' ][] = [
					'value' => $header->nodeValue,
					'id' => $header->getAttribute( 'id' ),
					'childs' => [],
				];
				$pos[ 2 ] = sizeof( $containTable[ $pos[ 1 ] ][ 'childs' ] ) - 1;
				$pos[ 3 ] = 0;
				break;
				
			case 'h3':
				$containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][] = [
					'value' => $header->nodeValue,
					'id' => $header->getAttribute( 'id' ),
					'childs' => [],
				];
				$pos[ 3 ] = sizeof( $containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ] ) - 1;
				$pos[ 4 ] = 0;
				break;
				
			case 'h4':
				$containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][ $pos[ 3 ] ][ 'childs' ][] = [
					'value' => $header->nodeValue,
					'id' => $header->getAttribute( 'id' ),
					'childs' => [],
				];
				$pos[ 4 ] = sizeof( $containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][ $pos[ 3 ] ][ 'childs' ] ) - 1;
				$pos[ 5 ] = 0;
				break;
				
			case 'h5':
				$containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][ $pos[ 3 ] ][ 'childs' ][ $pos[ 4 ] ][ 'childs' ][] = [
					'value' => $header->nodeValue,
					'id' => $header->getAttribute( 'id' ),
					'childs' => [],
				];
				$pos[ 5 ] = sizeof( $containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][ $pos[ 3 ] ][ 'childs' ][ $pos[ 4 ] ][ 'childs' ] ) - 1;
				$pos[ 6 ] = 0;
				break;
				
			case 'h6':
				$containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][ $pos[ 3 ] ][ 'childs' ][ $pos[ 4 ] ][ 'childs' ][ $pos[ 5 ] ][ 'childs' ][] = [
					'value' => $header->nodeValue,
					'id' => $header->getAttribute( 'id' ),
					'childs' => [],
				];
				$pos[ 6 ] = sizeof( $containTable[ $pos[ 1 ] ][ 'childs' ][ $pos[ 2 ] ][ 'childs' ][ $pos[ 3 ] ][ 'childs' ][ $pos[ 4 ] ][ 'childs' ][ $pos[ 5 ] ][ 'childs' ] ) - 1;
				$pos[ 7 ] = 0;
				break;
		}
	}
	
	return $containTable;
}

function sdztc_getTableItem( $data, $atts = [] ) {
	$style = '';
	if( isset( $atts[ 'link_color' ] ) && $atts[ 'link_color' ] ) {
		$style = 'style="color: ' . $atts[ 'link_color' ] . ';"';
	}
	
	$output = '';
	
	if( $data[ 0 ] !== null ) {
		$output .= '<ul>';
		foreach( $data as $item ) {
			if( $item[ 'value' ] ) {
				$output .= '<li><a href="#' . $item[ 'id' ] . '" ' . $style . '>' . $item[ 'value' ] . '</a></li>';
			}
			if( sizeof( $item[ 'childs' ] ) ) {
				$output .= sdztc_getTableItem( $item[ 'childs' ], $atts );
			}
		}
		$output .= '</ul>';
	}
	else {
		if( isset( $data[ null ][ 'childs' ] ) && sizeof( $data[ null ][ 'childs' ] ) ) {
			$output .= sdztc_getTableItem( $data[ null ][ 'childs' ], $atts );
		}
	}
	
	return $output;
}

function sdztc_getContainTable( $html, $atts = [] ) {
	$output = '';
	$data = sdztc_getHeaders( $html );
	
	$output .= '<div class="sdz_table_contain_container">';
	$output .= '<div class="sdz_table_contain_title">';
	if( isset( $atts[ 'title' ] ) && $atts[ 'title' ] ) {
		$output .= '<span>' . _( esc_attr( $atts[ 'title' ] ) ) . '</span>';
	}
	else {
		$output .= '<span></span>';
	}
	if( isset( $atts[ 'hide_button' ] ) && $atts[ 'hide_button' ] ) {
		$output .= '<button class="button btn btn-primary sdz_toogle_button" data-text-show="' . $atts[ 'text_show' ] . '" data-text-hide="' . $atts[ 'text_hide' ] . '">' . $atts[ 'text_hide' ] . '</button>';
	}
	$output .= '</div>';
	
	$output .= '<div class="sdz_table_contain_table">';
	$output .= sdztc_getTableItem( $data, $atts );
	$output .= '</div>';
	
	$output .= '</div>';
	
	return $output;
}
?>
