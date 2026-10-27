"""
Practical 15 - Final Portfolio Submission
Generate Test Report DOCX
Student: Krisha Patel | ID: 25CS069
"""

import zipfile, os, shutil
from datetime import datetime

BASE = r"d:\WDF____\StudentHub\Practical_15"
OUT  = os.path.join(BASE, "Practical_15_Test_Report.docx")
SHOTS= os.path.join(BASE, "screenshots")

def esc(t):
    return str(t).replace("&","&amp;").replace("<","&lt;").replace(">","&gt;").replace('"',"&quot;")

def img_rel(rid, path):
    with open(path,"rb") as f: data=f.read()
    ext = os.path.splitext(path)[1].lower().replace(".","")
    if ext=="jpg": ext="jpeg"
    return data, ext, f"image{rid}.{ext}"

TITLE   = "Practical 15 – Final Portfolio Submission"
STUDENT = "Krisha Patel"
SID     = "25CS069"
DATE    = datetime.now().strftime("%d %B %Y")

test_cases = [
    ("TC-01","Integrated Portfolio Dashboard",
     "Open the main page showing hero banner, live stats, student info badge.",
     "Dashboard displays with stats cards, student name, and 'All Practicals Completed' badge.",
     "PASS","01_portfolio_dashboard_hero.png"),
    ("TC-02","GitHub Repository & Practicals Overview",
     "Scroll to view the GitHub repo banner and all practicals cards grid.",
     "GitHub link displayed, all 7 practicals listed as cards with status badges.",
     "PASS","02_github_repo_and_practicals.png"),
    ("TC-03","Data Tables – Students & Events",
     "View the recent students table and upcoming events table with live data.",
     "Tables show seeded student records with GPAs and events with dates/venues.",
     "PASS","03_data_tables_students_events.png"),
    ("TC-04","Security Checklist Summary",
     "View the security checklist section listing all implemented security controls.",
     "All 12 security items show green check marks (bcrypt, PDO, XSS, CSRF, etc.).",
     "PASS","04_security_checklist_main.png"),
    ("TC-05","Full Test & Security Report Page",
     "Navigate to test_security_report.php showing comprehensive test results.",
     "Report shows 55 tests passed (100%), security checklist, accessibility, DB overview.",
     "PASS","05_test_security_report.png"),
]

# ── build XML ────────────────────────────────────────────────────────────────

def make_heading(text, level=1):
    sz = "36" if level==1 else ("28" if level==2 else "24")
    bold = "<w:b/>" if level<=2 else ""
    color = "1F3864" if level==1 else ("2E74B5" if level==2 else "404040")
    return f"""<w:p>
  <w:pPr><w:pStyle w:val="Heading{level}"/><w:jc w:val="center"/></w:pPr>
  <w:r><w:rPr>{bold}<w:color w:val="{color}"/><w:sz w:val="{sz}"/><w:szCs w:val="{sz}"/></w:rPr>
  <w:t>{esc(text)}</w:t></w:r></w:p>"""

def make_para(text, bold=False, center=False, size="22", color="000000"):
    b = "<w:b/>" if bold else ""
    jc = f'<w:jc w:val="center"/>' if center else ""
    return f"""<w:p>
  <w:pPr><w:spacing w:after="80"/>{jc}</w:pPr>
  <w:r><w:rPr>{b}<w:color w:val="{color}"/><w:sz w:val="{size}"/><w:szCs w:val="{size}"/></w:rPr>
  <w:t xml:space="preserve">{esc(text)}</w:t></w:r></w:p>"""

def make_image_para(rid, w_emu, h_emu):
    return f"""<w:p>
  <w:pPr><w:jc w:val="center"/><w:spacing w:before="100" w:after="100"/></w:pPr>
  <w:r><w:drawing>
    <wp:inline xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">
      <wp:extent cx="{w_emu}" cy="{h_emu}"/>
      <wp:docPr id="{rid}" name="Image{rid}"/>
      <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
        <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
          <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
            <pic:nvPicPr>
              <pic:cNvPr id="{rid}" name="Image{rid}"/>
              <pic:cNvPicPr/>
            </pic:nvPicPr>
            <pic:blipFill>
              <a:blip r:embed="rId{rid}" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>
              <a:stretch><a:fillRect/></a:stretch>
            </pic:blipFill>
            <pic:spPr>
              <a:xfrm><a:off x="0" y="0"/><a:ext cx="{w_emu}" cy="{h_emu}"/></a:xfrm>
              <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>
            </pic:spPr>
          </pic:pic>
        </a:graphicData>
      </a:graphic>
    </wp:inline>
  </w:drawing></w:r></w:p>"""

def page_break():
    return "<w:p><w:r><w:br w:type=\"page\"/></w:r></w:p>"

def table_row(*cells, header=False):
    bold = "<w:b/>" if header else ""
    bg   = "<w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"1F3864\"/>" if header else ""
    tc   = ""
    for c in cells:
        color = "FFFFFF" if header else "000000"
        tc += f"""<w:tc><w:tcPr><w:tcBorders>
          <w:top w:val="single" w:sz="4"/><w:left w:val="single" w:sz="4"/>
          <w:bottom w:val="single" w:sz="4"/><w:right w:val="single" w:sz="4"/>
        </w:tcBorders>{bg}</w:tcPr>
        <w:p><w:r><w:rPr>{bold}<w:color w:val="{color}"/><w:sz w:val="18"/></w:rPr>
        <w:t xml:space="preserve">{esc(c)}</w:t></w:r></w:p></w:tc>"""
    return f"<w:tr>{tc}</w:tr>"

# ── assemble body paragraphs ─────────────────────────────────────────────────

body_parts = []

# Cover
body_parts.append(make_para("", center=True))
body_parts.append(make_para("STUDENT HUB PORTAL", bold=True, center=True, size="40", color="1F3864"))
body_parts.append(make_para("Web Development Fundamentals – WDF", center=True, size="26", color="2E74B5"))
body_parts.append(make_para("", center=True))
body_parts.append(make_para(TITLE, bold=True, center=True, size="32", color="1F3864"))
body_parts.append(make_para("Test & Evaluation Report", center=True, size="24", color="404040"))
body_parts.append(make_para("", center=True))
body_parts.append(make_para(f"Student Name : {STUDENT}", bold=True, center=True, size="24"))
body_parts.append(make_para(f"Student ID     : {SID}", bold=True, center=True, size="24"))
body_parts.append(make_para(f"Date              : {DATE}", center=True, size="22"))
body_parts.append(page_break())

# Objective
body_parts.append(make_heading("1. Practical Objective", 2))
body_parts.append(make_para(
    "Practical 15 is the Final Portfolio Submission for the StudentHub Portal. It integrates "
    "all previous practicals (P9-P14) into a unified dashboard, provides a complete SQL database "
    "dump, a comprehensive README with setup instructions, and a full test & security report "
    "covering all implemented features across the entire project.", size="22"))
body_parts.append(make_para(""))

# Deliverables
body_parts.append(make_heading("2. Portfolio Deliverables", 2))
deliverables = [
    "GitHub Repository Link: https://github.com/krisha-patel-25CS069/studenthub-wdf",
    "Complete Database Dump: studenthub_complete.sql (all 5 tables with seed data)",
    "README.md: Full setup, installation, testing instructions, and project structure",
    "Test & Security Report: 55 test cases across 7 practicals, 100% pass rate",
    "Interactive Dashboard: Integrated portal showing live stats from all modules",
    "Screenshots / Demo: 5 captured screenshots demonstrating the full portal",
    "Submission ZIP: Practical_15_25CS069.zip containing all deliverables",
]
for d in deliverables:
    body_parts.append(make_para(f"  • {d}", size="22"))
body_parts.append(make_para(""))

# Test Results Table
body_parts.append(make_heading("3. Test Results Summary", 2))
tbl = "<w:tbl><w:tblPr><w:tblStyle w:val=\"TableGrid\"/><w:tblW w:w=\"9200\" w:type=\"dxa\"/></w:tblPr>"
tbl += "<w:tblGrid>" + "".join(["<w:gridCol w:w=\"1100\"/>","<w:gridCol w:w=\"2200\"/>","<w:gridCol w:w=\"2000\"/>","<w:gridCol w:w=\"2000\"/>","<w:gridCol w:w=\"900\"/>","<w:gridCol w:w=\"1000\"/>"])+"</w:tblGrid>"
tbl += table_row("Test ID","Test Case","Steps","Expected","Result","Screenshot", header=True)
for tc in test_cases:
    tbl += table_row(tc[0], tc[1], tc[2][:60]+"..." if len(tc[2])>60 else tc[2],
                     tc[3][:55]+"..." if len(tc[3])>55 else tc[3], tc[4], tc[5])
tbl += "</w:tbl>"
body_parts.append(tbl)
body_parts.append(make_para(""))

# Screenshots
body_parts.append(page_break())
body_parts.append(make_heading("4. Screenshots", 2))

image_data = []
rid = 1

for tc in test_cases:
    img_path = os.path.join(SHOTS, tc[5])
    if not os.path.exists(img_path):
        print(f"  [SKIP] {tc[5]} not found")
        continue
    data, ext, fname = img_rel(rid, img_path)
    image_data.append((rid, fname, data, ext))
    body_parts.append(make_para(f"{tc[0]}: {tc[1]}", bold=True, size="22", color="1F3864"))
    body_parts.append(make_para(tc[2], size="20", color="404040"))
    body_parts.append(make_image_para(rid, 6000000, 3800000))
    body_parts.append(make_para(""))
    rid += 1

# Cross-practical summary
body_parts.append(page_break())
body_parts.append(make_heading("5. Cross-Practical Test Summary", 2))
cross_tbl = "<w:tbl><w:tblPr><w:tblStyle w:val=\"TableGrid\"/><w:tblW w:w=\"9200\" w:type=\"dxa\"/></w:tblPr>"
cross_tbl += "<w:tblGrid><w:gridCol w:w=\"1200\"/><w:gridCol w:w=\"3000\"/><w:gridCol w:w=\"1200\"/><w:gridCol w:w=\"1200\"/><w:gridCol w:w=\"1200\"/><w:gridCol w:w=\"1400\"/></w:tblGrid>"
cross_tbl += table_row("Practical","Module","Tests","Passed","Rate","Key Feature", header=True)
cross_data = [
    ("P9","User Registration & Auth","6","6","100%","bcrypt"),
    ("P10","Role-Based Sessions","9","9","100%","CSRF"),
    ("P11","Student Records CRUD","6","6","100%","CRUD"),
    ("P12","Event Mgmt & Upload","10","10","100%","File Upload"),
    ("P13","REST API + Fetch SPA","12","12","100%","REST"),
    ("P14","RBAC & Audit Trail","7","7","100%","Audit"),
    ("P15","Final Portfolio","5","5","100%","Integration"),
]
for cd in cross_data:
    cross_tbl += table_row(*cd)
cross_tbl += table_row("TOTAL","All Practicals","55","55","100%","All Features", header=False)
cross_tbl += "</w:tbl>"
body_parts.append(cross_tbl)
body_parts.append(make_para(""))

# Security
body_parts.append(make_heading("6. Security Checklist", 2))
security = [
    ("Password Hashing",       "bcrypt via password_hash() / password_verify()",   "PASS"),
    ("SQL Injection Prevention","PDO prepared statements throughout",               "PASS"),
    ("XSS Prevention",         "htmlspecialchars() on all output",                  "PASS"),
    ("CSRF Token Protection",  "Random token in session, verified on POST",         "PASS"),
    ("Session Security",       "session_regenerate_id() on login/logout",           "PASS"),
    ("Role-Based Access",      "Role check on every protected route, 403 on fail",  "PASS"),
    ("Audit Trail",            "Every action logged with user ID, IP, timestamp",   "PASS"),
    ("File Upload Validation", "MIME type whitelist + size limit enforced",          "PASS"),
    ("HTTP-Only Cookies",      "session.cookie_httponly=1",                         "PASS"),
    ("Input Validation",       "Server-side validation on all forms",               "PASS"),
    ("Error Handling",         "Generic errors; no stack traces exposed",           "PASS"),
    ("REST API Security",      "JSON content-type, input sanitisation, PDO",        "PASS"),
]
sec_tbl = "<w:tbl><w:tblPr><w:tblStyle w:val=\"TableGrid\"/><w:tblW w:w=\"9200\" w:type=\"dxa\"/></w:tblPr>"
sec_tbl += "<w:tblGrid><w:gridCol w:w=\"2200\"/><w:gridCol w:w=\"5000\"/><w:gridCol w:w=\"2000\"/></w:tblGrid>"
sec_tbl += table_row("Security Item","Implementation","Status", header=True)
for s in security:
    sec_tbl += table_row(*s)
sec_tbl += "</w:tbl>"
body_parts.append(sec_tbl)
body_parts.append(make_para(""))

# Conclusion
body_parts.append(make_heading("7. Conclusion", 2))
body_parts.append(make_para(
    "Practical 15 successfully consolidates the entire StudentHub Portal project "
    "into a final portfolio submission. All 55 test cases across 7 practicals "
    "passed with a 100% success rate. The integrated dashboard provides a single-page "
    "overview of all modules, database tables, security controls, and test results. "
    "The complete SQL dump, comprehensive README, and this test report fulfill all "
    "submission requirements for the Web Development Fundamentals course.", size="22"))

body_xml = "\n".join(body_parts)

# ── Word XML files ────────────────────────────────────────────────────────────

CONTENT_TYPES = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Default Extension="png"  ContentType="image/png"/>
  <Default Extension="jpeg" ContentType="image/jpeg"/>
  <Override PartName="/word/document.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>"""

RELS = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"
    Target="word/document.xml"/>
</Relationships>"""

WORD_RELS_HEAD = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId0" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles"
    Target="styles.xml"/>
"""

STYLES = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:styleId="Normal"><w:name w:val="Normal"/>
    <w:rPr><w:sz w:val="22"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/>
    <w:rPr><w:b/><w:sz w:val="36"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading2"><w:name w:val="heading 2"/>
    <w:rPr><w:b/><w:sz w:val="28"/></w:rPr></w:style>
  <w:style w:type="table" w:styleId="TableGrid"><w:name w:val="Table Grid"/>
    <w:tblPr><w:tblBorders>
      <w:top w:val="single" w:sz="4"/><w:left w:val="single" w:sz="4"/>
      <w:bottom w:val="single" w:sz="4"/><w:right w:val="single" w:sz="4"/>
      <w:insideH w:val="single" w:sz="4"/><w:insideV w:val="single" w:sz="4"/>
    </w:tblBorders></w:tblPr></w:style>
</w:styles>"""

DOCUMENT = f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
            xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
<w:body>
<w:sectPr>
  <w:pgSz w:w="12240" w:h="15840"/>
  <w:pgMar w:top="1080" w:right="1080" w:bottom="1080" w:left="1080"/>
</w:sectPr>
{body_xml}
</w:body>
</w:document>"""

# ── write docx ────────────────────────────────────────────────────────────────

if os.path.exists(OUT): os.remove(OUT)

with zipfile.ZipFile(OUT, "w", zipfile.ZIP_DEFLATED) as z:
    z.writestr("[Content_Types].xml", CONTENT_TYPES)
    z.writestr("_rels/.rels", RELS)
    z.writestr("word/styles.xml", STYLES)

    img_rels = ""
    for (r_id, fname, data, ext) in image_data:
        img_rels += f'  <Relationship Id="rId{r_id}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/{fname}"/>\n'
        z.writestr(f"word/media/{fname}", data)
    word_rels = WORD_RELS_HEAD + img_rels + "</Relationships>"
    z.writestr("word/_rels/document.xml.rels", word_rels)
    z.writestr("word/document.xml", DOCUMENT)

print(f"Done: {OUT}")
