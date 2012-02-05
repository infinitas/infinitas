/**
 * @inital author: Karl-Johan SjÃ¶gren / http://blog.crazybeavers.se/
 * @contributor: Joost Elfering / http://yopefonic.wordpress.com/
 * @url: http://blog.crazybeavers.se/wp-content/demos/jquery.tag.editor/
 * @license: Creative Commons License - ShareAlike http://creativecommons.org/licenses/by-sa/3.0/
 * @version: 1.4
 * @changelog
 */

(function($) {
    $.fn.tagEditor = function(options) {
        var defaults = {
            separator: ',',
            items: [],
            className: 'tagEditor',
            confirmRemoval: false,
            confirmRemovalText: 'Do you really want to remove the tag?',
            completeOnSeparator: false,
            completeOnBlur: false,
            tagsBeforeField: false,
            initialParse: true,
            imageTag: false,
            imageTagUrl: '',
            continuousOutputBuild: false
        }

        if (options) {
            options = $.extend(defaults, options);
        } else {
            options = defaults;
        }
        var listBase, textBase = this, hiddenText;
        var itemBase = [];

        return this.each(function() {
            hiddenText = $(document.createElement('input'));
            hiddenText.attr('type', 'hidden');
            if (options.continuousOutputBuild) {
                hiddenText.attr('name', textBase.attr('name'));
                textBase.attr('name', textBase.attr('name') + '_old');
            }
            textBase.after(hiddenText);

            listBase = $(document.createElement('ul'));
            listBase.attr('class', options.className);
            if (options.tagsBeforeField)
                $(this).before(listBase);
            else
                $(this).after(listBase);

            for (var i = 0; i < options.items.length; i++)
                addTag($.trim(options.items[i]));

            if (options.initialParse)
                parse();

            if (options.completeOnBlur)
                $(this).blur(parse);

            buildArray();
            $(this).keypress(handleKeys);

            var form = $(this).parents('form');

            if (!options.continuousOutputBuild) {
                form.submit(function() {
                    parse();
                    hiddenText.val(itemBase.join(options.separator));
                    hiddenText.attr('id', textBase.attr('id'));
                    hiddenText.attr("name", textBase.attr('name'));
                    textBase.attr('id', textBase.attr('id') + '_old');
                    textBase.attr('name', textBase.attr('name') + '_old');

                });
            }

            function addTag(tag) {
                tag = $.trim(tag);
                for (var i = 0; i < itemBase.length; i++) {
                    if (itemBase[i].toLowerCase() == tag.toLowerCase())
                        return false;
                }

                var item = $(document.createElement('li'));
                item.text(tag);
                item.attr('title', 'Remove tag');
                if (options.imageTag)
                    item.append('<img src="' + options.imageTagUrl + '">');

                item.click(function() {
                    if (options.confirmRemoval)
                        if (!confirm(options.confirmRemovalText))
                        return;

                    item.remove();
                    parse();
                });

                listBase.append(item);
                return true;
            }

            function resetTags() {
                itemBase = [];
                listBase.html('');
                textBase.val('');
                hiddenText.val('');
                for (var i = 0; i < options.items.length; i++) {
                    addTag($.trim(options.items[i]));
                }
                parse();
            }

            function buildArray() {
                itemBase = [];
                var items = $('li', listBase);

                for (var i = 0; i < items.length; i++)
                    itemBase.push($.trim($(items[i]).text()));

                if (options.continuousOutputBuild)
                    hiddenText.val(itemBase.join(options.separator));
            }

            function parse() {
                var items = textBase.val().split(options.separator);

                for (var i = 0; i < items.length; i++) {
                    var trimmedItem = $.trim(items[i]);
                    if (trimmedItem.length > 0)
                        addTag(trimmedItem);
                }

                textBase.val("");
                buildArray();
            }

            function handleKeys(ev) {
                var keyCode = (ev.which) ? ev.which : ev.keyCode;

                if (options.completeOnSeparator) {
                    if (String.fromCharCode(keyCode) == options.separator) {
                        parse();
                        return false;
                    }
                }

                switch (keyCode) {
                    case 13:
                        if ($.trim(textBase.val()) != '') {
                            parse();
                            return false;
                        }
                        return true;
                        break;
                }
            }

            $.fn.extend({
                tagEditorGetTags: function() {
                    return itemBase.join(options.separator);
                },
                tagEditorResetTags: function() {
                    resetTags();
                },
                tagEditorAddTag: function(tag) {
                    return addTag(tag);
                }
            });
        });
    }
})(jQuery);