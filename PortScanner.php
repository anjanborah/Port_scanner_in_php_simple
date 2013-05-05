<?php
/*
* A simple programme for scanning ports
* GitHub - https://github.com/anjanborah/Port_scanner_in_php_simple
* Author - Anjan Borah < anjanborah@aol.com >
* Copyright ( c ) 2013 Anjan Borah
*/

error_reporting( 0 );

class Scan {
  
  public function __construct() {
    try {
      $this->check_OS();
      $this->get_host();
      $this->check_is_IP();
      if( $this->is_IP == true ) {
        $this->host_IP_address = $this->host_name_user_input;
      } else {
        if( gethostbyname( $this->host_name_user_input ) == $this->host_name_user_input ) {
          throw new Exception( "Can not find the IP address of ".$this->host_name_user_input );
        } else {
          $this->host_IP_address = ( string )gethostbyname( $this->host_name_user_input );
        }
      }
      $this->display_host_information();
      $this->scan();
    } catch( Exception $exception ) {
      print "\tException  - ".$exception->getMessage()."\n";
    }
  }
  
  private function check_OS() {
    $this->os_name = php_uname();
    if( strtolower( substr( $this->os_name, 0, 3 ) ) == "lin" ) {
      print "Hi\n";
      system( "clear");
    } elseif( strtolower( substr( $this->os_name, 0, 3 ) ) == "win" ) {
      system( "cls" );
    } else {
      /*
       * Not using Windows or Linux
       */
    }
  }
  
  private function get_host() {
    print "\tHost - ";
    $this->host_name_user_input = trim( ( string )fgets( STDIN ) );
  }
  
  public function check_is_IP() {
    $host_exploded = explode( '.', $this->host_name_user_input );
    if( count( $host_exploded ) == 4 ) {
      foreach( $host_exploded as $key => $value ) {
        if( ( integer )$value >= 0 and ( integer )$value <= 255 ) {
          $this->is_IP = true;
        } else {
          $this->is_IP = false;
          break;
        }
      }
    } else {
      $this->is_IP = false;
    }
  }
  
  private function display_host_information() {
    if( $this->is_IP == true ) {
      print "\n";
      print "\tHost information - \n";
      print "\tHost name       - ".( string )gethostbyaddr( $this->host_IP_address )."\n";
      print "\tHost IP address - ".$this->host_IP_address."\n";
    } elseif( $this->is_IP == false ) {
      print "\n";
      print "\tHost information - \n";
      print "\tHost name       - ".( string )gethostbyaddr( $this->host_IP_address )."\n";
      print "\tHost IP address - ".$this->host_IP_address."\n";
      $ip_addresses = gethostbynamel( $this->host_name_user_input );
      if( $ip_addresses != false ) {
        if( count( $ip_addresses ) > 1 ) {
          foreach( $ip_addresses as $key => $value ) {
            print "\t - ".$value."\n";
          }
        }
      }
    }
    print "\n";
  }
  
  private function scan() {
    print "\tscan start port number - ";
    $start_port_number = ( integer )trim( fgets( STDIN ) );
    print "\tscan end port number   - ";
    $end_port_number = ( integer )trim( fgets( STDIN ) );
    for( $i=$start_port_number; $i<=$end_port_number; $i++ ) {
      print "\r\t Scanning port - ".$i;
      $scan_socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
      $time_out = array( 'sec' => 2, 'usec' => 0 );
      socket_set_option( $scan_socket, SOL_SOCKET, SO_RCVTIMEO, $time_out );
      $connected = socket_connect( $scan_socket, $this->host_IP_address, $i );
      if( $connected == true ) {
        print "\r\t [ port - ".$i."\t\topen ]\n";
      }
      socket_close( $scan_socket );
    }
  }
  
  public function __destruct() {
  }
  
  private $os_name;
  private $host_name_user_input;
  private $is_IP;
  private $host_IP_address;
}

function main( $argc, $argv ) {
  $object = new Scan();
}

main( $argc, $argv );

?>
