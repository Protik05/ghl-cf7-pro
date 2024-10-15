<?php

    class Ghl_Cf7_Pro_Updater {
        
        
		public $cache_key;
		public $cache_allowed;
        private $_version;
        private $_slug;
        private $_path;
		
		public function __construct() {
		    
			$this->cache_key = 'ghlcf7pro_updater';
			$this->cache_allowed = true;
            $this->_version = GHL_CF7_PRO_VERSION;
            $this->_slug = GHLCF7PRO_PLUGIN_BASENAME;
            $this->_path = GHLCF7PRO_PATH;
			delete_transient( $this->cache_key );
		}
        
        // Plugin update
        public function ghlcf7pro_request() {
            
			$remote = get_transient( $this->cache_key );
            
			if( false === $remote ) {
			    
			    $license_key =get_option('ghlcf7pro-lic-keys');
			    
			    if( empty( $license_key ) ) $license_key = '';
			    
                $remote = wp_remote_get( 
                	add_query_arg( 
                		array(
                			'license_key' => urlencode( $license_key )
                		), 
                		'https://server.ibsofts.com/ghlcf7pro/ghlcf7pro-info.php' //need to change the file path
                	), 
                
                	array(
                		'timeout' => 10,
                		'headers' => array(
                			'Accept' => 'application/json'
                		)
                	)
                );

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}

				set_transient( $this->cache_key, $remote, 864000 );

			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ) );
			return $remote;

		}

		function ghlcf7pro_info( $res, $action, $args ) {
		    		    
			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return $res;
			}

			// do nothing if it is not our plugin
			if( $this->_slug !== $args->slug ) {
				return $res;
			}

			// get updates
			$remote = $this->ghlcf7pro_request();

			if( ! $remote ) {
				return $res;
			}
			
			$res = new stdClass();

			$res->name = $remote->name;
			$res->slug = $remote->slug;
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = $remote->author;
			$res->author_profile = $remote->author_profile;
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->requires_php = $remote->requires_php;
			$res->last_updated = $remote->last_updated;
			
			

			$res->sections = array(
				'description' => $remote->sections->description,
				/*'installation' => $remote->sections->installation,*/
				'changelog' => $remote->sections->changelog
			);

			if( ! empty( $remote->banners ) ) {
				$res->banners = array(
					'low' => $remote->banners->low,
					'high' => $remote->banners->high
				);
			}
			return $res;

		}

		public function ghlcf7pro_update( $transient ) {

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->ghlcf7pro_request();
            
			if(
				$remote
				&& version_compare( $this->_version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
                
				$res = new stdClass();
				$res->slug = $this->_slug;
				$res->plugin = $this->_path;
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->package = $remote->download_url;

				$transient->response[ $res->plugin ] = $res;

	        }

			return $transient;

		}

		public function ghlcf7pro_purge( $upgrader, $options ){

			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'plugin' === $options[ 'type' ]
			) {
				// just clean the cache when new plugin version is installed
				delete_transient( $this->cache_key );
			}

		}
		
        public function ghlcf7pro_update_message( $plugin_info_array, $plugin_info_object ) {
            
        	if( empty( $plugin_info_array[ 'package' ] ) ) {
        		echo ' Please renew your license to update. You can change your license key in GHL Connect For Woocommerce Pro> License > Enter Your License';
        	}
        	
        }
        
    }