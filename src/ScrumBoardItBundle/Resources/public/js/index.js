$(function () {
    $(".panel").each(function () {
        panel = $(this);
        panel.find('.panel-heading .badge').text(panel.find('ul').children("li").length);
    });
    $(".selectable").selectable({filter: "li"});
    $.contextMenu({
        // define which elements trigger this menu
        selector: "li.ui-selected",
        // define the elements of the menu
        items: {
            opt: {
                name: "Envoyer à l'impression",
                callback: function (key, opt) {
                    panel = opt.$trigger.parents(".panel");
                    list = opt.$trigger.parent();
                    selected = list.children("li.ui-selected");
                    selected.removeClass("ui-selected").appendTo("#printQueue");
                    panel.find(".panel-heading .badge").text(list.children("li").length);
                    $("#printQueue").parents(".panel").find(".panel-heading .badge").text($("#printQueue li").length);
                }
            },
        }
    });
    $("#submitPrint").click(function () {
        $form = $(this).parents('form');
        $form.attr('action', "{{ path('print') }}");
        $form.find(':checkbox').prop('checked', true);
        $form.attr('target', '_blank');
        $form.submit();
    });
    $("#addFlag").click(function () {
        $form = $(this).parents('form');
        $form.attr('action', "{{ path('add_flag') }}");
        $form.find(':checkbox').prop('checked', true);
        $form.submit();

    });
    $("#issues_search").find('select').change(function() {
        $form = $(this).parents('form');
        if ($(this).hasClass('search_project') && $('.search_sprint').size()) {
            $('.search_sprint').val('');
            $('.search_sprint').attr('disabled', 'disabled');
        }
        $form.submit();
    });
});

$('.toggle').click(function (event) {
    event.preventDefault();
    var target = $(this).attr('href');
    $(target).toggleClass('hidden show');

    animateClass = " btn-rotation";
    $('.toggle').addClass( animateClass );
    $('.toggle').toggleClass('counter-clockwise clockwise');

    window.setTimeout( function() {
      $('.toggle').removeClass( animateClass );
    }, 200 );
});
