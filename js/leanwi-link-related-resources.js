document.addEventListener("click", function(e) {
  if (e.target && e.target.classList.contains("related-resources-link")) {
    e.preventDefault();

    // Parse data from the clicked link
    const related = JSON.parse(e.target.getAttribute("data-related"));

    // Build popup HTML
    let popupContent = `
      <html>
        <head>
          <title>Related Resources</title>
          <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            h3 { margin-top: 0; }
            ul { line-height: 1.6; }
            a { color: #0066cc; text-decoration: none; }
            a:hover { text-decoration: underline; }
          </style>
        </head>
        <body>
          <h3>Related Resources</h3>
          <ul>
            ${related.map(r => 
              `<li><a href="${r.link_url}" target="_blank" rel="noopener" 
                   title="${r.description || ''}">
                   ${r.title}
               </a></li>`
            ).join('')}
          </ul>
        </body>
      </html>
    `;

    // Set popup size
    const popupWidth = 600;
    const popupHeight = 400;

    // Calculate center position relative to current window
    const left = window.screenX + (window.innerWidth - popupWidth) / 2;
    const top = window.screenY + (window.innerHeight - popupHeight) / 2;

    const popup = window.open(
      "",
      "relatedResources",
      `width=${popupWidth},height=${popupHeight},left=${left},top=${top},scrollbars=yes,resizable=yes`
    );
    popup.document.open();
    popup.document.write(popupContent);
    popup.document.close();
  }
});