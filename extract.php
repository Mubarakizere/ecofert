<?php
$log_file = 'C:\Users\mouba\.gemini\antigravity-ide\brain\30fc16ab-75b0-4313-9d07-e229d261d12a\.system_generated\logs\transcript.jsonl';
$out_file = 'C:\Users\mouba\.gemini\antigravity-ide\brain\30fc16ab-75b0-4313-9d07-e229d261d12a\scratch\proposal.txt';

$last_user_msg = "";
$handle = fopen($log_file, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $data = json_decode($line, true);
        if (isset($data['type']) && $data['type'] === 'USER_INPUT') {
            if (isset($data['content'])) {
                $last_user_msg = $data['content'];
            }
        }
    }
    fclose($handle);
}

// Make sure the scratch directory exists
$dir = dirname($out_file);
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

file_put_contents($out_file, $last_user_msg);
echo "Done";
