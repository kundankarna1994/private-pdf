<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.min.js"></script>
</head>

<body>
    {{-- <embed src="data:application/pdf;base64,{{ $file }}" type="application/pdf" width="100%" height="500"> --}}
    <div>
        <button id="prev">Previous</button>
        <button id="next">Next</button>
        &nbsp; &nbsp;
        <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
        <canvas id="the-canvas"></canvas>
    </div>
    <script type="module">
        const content = atob("{{ $file }}");

        var loadingTask = pdfjsLib.getDocument({
            data: content
        });

        loadingTask.promise.then(
            function(pdfDoc) {
                    var pageNum = 1,
                    pageRendering = false,
                    pageNumPending = null,
                    scale = 1.5,
                    canvas = document.getElementById("the-canvas"),
                    ctx = canvas.getContext("2d");

                function renderPage(num) {
                    pageRendering = true;
                    // Using promise to fetch the page
                    pdfDoc.getPage(num).then(function(page) {
                        var viewport = page.getViewport({
                            scale: scale
                        });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        canvas.oncontextmenu = function(e) { e.preventDefault(); e.stopPropagation(); }
                        // Render PDF page into canvas context
                        var renderContext = {
                            canvasContext: ctx,
                            viewport: viewport,
                        };
                        var renderTask = page.render(renderContext);

                        // Wait for rendering to finish
                        renderTask.promise.then(function() {
                            pageRendering = false;
                            if (pageNumPending !== null) {
                                // New page rendering is pending
                                renderPage(pageNumPending);
                                pageNumPending = null;
                            }
                        });
                        document.getElementById("page_num").textContent = num;
                    });
                }

                /**
                 * If another page rendering in progress, waits until the rendering is
                 * finised. Otherwise, executes rendering immediately.
                 */
                function queueRenderPage(num) {
                    if (pageRendering) {
                        pageNumPending = num;
                    } else {
                        renderPage(num);
                    }
                }

                /**
                 * Displays previous page.
                 */
                function onPrevPage() {
                    if (pageNum <= 1) {
                        return;
                    }
                    pageNum--;
                    queueRenderPage(pageNum);
                }
                document.getElementById("prev").addEventListener("click", onPrevPage);

                /**
                 * Displays next page.
                 */
                function onNextPage() {
                    if (pageNum >= pdfDoc.numPages) {
                        return;
                    }
                    pageNum++;
                    queueRenderPage(pageNum);
                }
                document.getElementById("next").addEventListener("click", onNextPage);
                document.getElementById('page_count').textContent = pdfDoc.numPages;
                renderPage(pageNum);
            },
            function(reason) {
                // PDF loading error
                console.error(reason);
            }
        );
    </script>
</body>

</html>
