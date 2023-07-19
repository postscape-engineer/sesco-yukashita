function faq() {
    let faqQuestion = $(".faq__q");
    faqQuestion.on("click", function () {
        $(this).toggleClass("open");
        $(this).next(".faq__a").slideToggle();
    });
}
function scroll() {
    $(".do__wrap").scroll(function (e) {
        $(this).find(".do__icon").fadeOut();
    });
}

$(function () {
    faq();
    scroll();
});
