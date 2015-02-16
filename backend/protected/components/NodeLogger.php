<?php
//use ElephantIO\Client as Elephant;

class NodeLogger {
    public static function sendLog($message){
        return;
        if($_SERVER["SERVER_NAME"] != "localhost"){
            
        }
        Yii::import('ext.ElephantIO.Client');
        
        if(!is_string($message)){
            $message = NodeLogger::renderJSON($message);
        }
        
        //$elephant = new Client('http://pukul.in:10001', 'socket.io', 1, false, true, true);
        $elephant = new Client('http://localhost:8000', 'socket.io', 1, false, true, true);
        
        $elephant->init();
        $elephant->emit('newmessage', date("Y-m-d H:i:s")."\n".$message);
        $elephant->close();
        
        //echo "SEND MESSAGE ".$message;
    }
    
    private static function renderJSON($jsonObj) {
        $json = CJSON::encode($jsonObj);
        $result = '';
        $level = 0;
        $prev_char = '';
        $in_quotes = false;
        $ends_line_level = NULL;
        $json_length = strlen($json);

        for ($i = 0; $i < $json_length; $i++) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if ($ends_line_level !== NULL) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ($char === '"' && $prev_char != '\\') {
                $in_quotes = !$in_quotes;
            } else if (!$in_quotes) {
                switch ($char) {
                    case '}': case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level = $level;
                        break;

                    case '{': case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ": case "\t": case "\n": case "\r":
                        $char = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level = NULL;
                        break;
                }
            }
            if ($new_line_level !== NULL) {
                $result .= "\n" . str_repeat("\t", $new_line_level);
            }
            $result .= $char . $post;
            $prev_char = $char;
        }

        return $result;
    }
}