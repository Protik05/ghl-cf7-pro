<?php

    class GHLCF7PRO_Log  {
        
        private $log_directory;

        public function __construct() {
            $this->log_directory = WP_CONTENT_DIR . '/ghlcf7pro-logs';
            wp_mkdir_p($this->log_directory);
        }
    
        private function write_log($message, $log_level = 'INFO') {
            
            $log_file = $this->log_directory . '/ghlcf7pro.log';
            
            // Get the WordPress timezone
            $blog_timezone = get_option('timezone_string');
            
            // Set the timezone for date/time functions
            if (!empty($blog_timezone)) {
                date_default_timezone_set($blog_timezone);
            }
        
            $log_entry = sprintf("[%s] [%s]: %s\n", date('Y-m-d H:i:s'), $log_level, $message);
            file_put_contents($log_file, $log_entry, FILE_APPEND);
        }
        
        public function display_log() {
            
            $log_file = $this->log_directory . '/ghlcf7pro.log';
        
            if (file_exists($log_file)) {
                $log_contents = file_get_contents($log_file);
                echo '<pre>' . esc_html($log_contents) . '</pre>';
            } else {
                echo 'Log file does not exist.';
            }
        }
    
        public function log_action($message) {
            $this->write_log($message);
        }
    
        public function log_error($message) {
            $this->write_log($message, 'ERROR');
        }
        
    }