<?php

function parse_ini_file_fix( $file )
{
    if( !file_exists( $file ) ) {
        return false;
    }

    $ini = array();

    $section = null;
    foreach( file( $file ) as $line ) {
        $line = trim( $line );

        // section heading
        if( !$line ) {
            continue;
        }
        else if( strpos( $line, '[' ) === 0 ) {
            $section = str_replace( array( '[', ']' ), '', $line );
            continue;
        }
        else if( strpos( $line, ';' ) === 0 ) {
            continue;
        }

        // get the key and val
        list( $key, $val ) = explode( '=', $line, 2 );
        $key = trim( $key );
        $val = trim( $val );

        // cleanup val
        $val = preg_replace( '/;[^"]*?$/i', '', $val );
        $val = trim( trim( $val ), '"' );
        $val = str_replace( array( 'yes', 'on', 'true' ), '1', $val );
        $val = str_replace( array( 'no', 'off', 'false', 'null' ), '', $val );


        // cleanup key
        if( strpos( $key, '[]' ) !== false ) {
        }
        else if( strpos( $key, '[' ) !== false ) {
            $key = str_replace( ']', '', $key );

            list( $key, $skey ) = explode( '[', $key, 2 );

            $tmp = array();

            // preload already set info
            if( $section ) {
                if( isset( $ini[ $section ][ $key ] ) && is_array( $ini[ $section ][ $key ] ) ) {
                    $tmp = $ini[ $section ][ $key ];
                }
            }
            else {
                if( isset( $ini[ $key ] ) && is_array( $ini[ $key ] ) ) {
                    $tmp = $ini[ $key ];
                }
            }

            // load in new data
            $tmp[ $skey ] = $val;

            // reset val to new array
            $val = $tmp;
        }


        // save to ini set
        if( $section ) {
            $ini[ $section ][ $key ] = $val;
        }
        else {
            $ini[ $key ] = $val;
        }
    }

    return $ini;
}

if( !function_exists( 'array_replace_recursive' ) ) {
    function array_replace_recursive( $array, $array1 ) {
        function recurse( $array, $array1 ) {
            foreach ($array1 as $key => $value) {
                // create new key in $array, if it is empty or not an array
                if( !isset( $array[ $key ] ) || ( isset( $array[ $key ] ) && !is_array( $array[ $key ] ) ) ) {
                    $array[$key] = array();
                }

                // overwrite the value in the base array
                if( is_array( $value ) ) {
                    $value = recurse( $array[ $key ], $value );
                }

                $array[ $key ] = $value;
            }
            return $array;
        }

        // handle the arguments, merge one by one
        $args = func_get_args();
        $array = $args[0];
        if( !is_array( $array ) ) {
            return $array;
        }

        for( $i = 1; $i < count( $args ); $i++ ) {
            if( is_array( $args[ $i ] ) ) {
                $array = recurse( $array, $args[ $i ] );
            }
        }

        return $array;
    }
}
