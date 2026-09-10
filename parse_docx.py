import zipfile
import xml.etree.ElementTree as ET
import sys

def parse_docx(docx_path):
    with zipfile.ZipFile(docx_path) as z:
        xml_content = z.read('word/document.xml')
    
    root = ET.fromstring(xml_content)
    ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
    
    # Iterate through body elements in order
    body = root.find('w:body', ns)
    if body is None:
        print("No body found")
        return

    output = []
    
    for elem in body:
        tag = elem.tag.split('}')[-1]
        if tag == 'p':
            texts = [t.text for t in elem.findall('.//w:t', ns) if t.text]
            p_text = "".join(texts).strip()
            if p_text:
                output.append(f"[P] {p_text}")
        elif tag == 'tbl':
            output.append("=== TABLE START ===")
            for row in elem.findall('.//w:tr', ns):
                row_cells = []
                for cell in row.findall('.//w:tc', ns):
                    cell_texts = [t.text for t in cell.findall('.//w:t', ns) if t.text]
                    cell_str = " ".join("".join(cell_texts).split())
                    row_cells.append(cell_str)
                output.append(" | ".join(row_cells))
            output.append("=== TABLE END ===")

    with open("docx_parsed.txt", "w", encoding="utf-8") as f:
        f.write("\n".join(output))

    print(f"Total lines parsed: {len(output)}")

if __name__ == "__main__":
    parse_docx(r"c:\Users\mouba\ecofert\EcoFert_Full_Dissertation.docx")
