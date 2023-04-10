function mousemove(event) {
    console.log("pageX: ", event.pageX,
        "pageY: ", event.pageY,
        "clientX: ", event.clientX,
        "clientY:", event.clientY)

    let imgElement = document.getElementById('box');

    imgElement.src = "../static/img/mango-by-DanielMcKeown-Feb2020-pt2.jpg"

    setTimeout(() => imgElement.src = "../static/img/mango-by-DanielMcKeown-Feb2020-pt1.jpg", 3000);
}

window.addEventListener('mousemove', mousemove);

let lastKnownScrollPosition = 0;
let ticking = false;

function doSomething(scrollPos) {
    // Do something with the scroll position
    console.log(scrollPos);
}

document.addEventListener('mousemove', function(e) {
    lastKnownScrollPosition = window.scrollY;

    if (!ticking) {
        window.requestAnimationFrame(function() {
            doSomething(lastKnownScrollPosition);
            ticking = false;
        });

        ticking = true;
    }
});