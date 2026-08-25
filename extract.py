import json
import sys

def main():
    log_file = r"C:\Users\mouba\.gemini\antigravity-ide\brain\30fc16ab-75b0-4313-9d07-e229d261d12a\.system_generated\logs\transcript.jsonl"
    out_file = r"C:\Users\mouba\.gemini\antigravity-ide\brain\30fc16ab-75b0-4313-9d07-e229d261d12a\scratch\proposal.txt"
    
    last_user_msg = ""
    with open(log_file, "r", encoding="utf-8") as f:
        for line in f:
            try:
                data = json.loads(line)
                if data.get("type") == "USER_INPUT":
                    last_user_msg = data.get("content", "")
            except:
                pass
                
    with open(out_file, "w", encoding="utf-8") as f:
        f.write(last_user_msg)

if __name__ == "__main__":
    main()
