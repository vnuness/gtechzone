$('.modal').on('show.bs.modal hidden.bs.modal', function (e) {

    var $html = $('html');

    if (e.type == 'show') {
        $html.css({'overflow-y': 'hidden'});
    } else {
        $html.css({'overflow-y': 'auto'});
    }

    return true;
});


const cookie = {
    get: (cname) => {

        blad = 'dafsdf';
        var name = cname + "=";
        var ca = document.cookie.split(';');

        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }

        return undefined;
    },
    set: (cname, cvalue, exdays) => {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
};

const helper = {

    alphanumericGen: function () {
        return Math.random().toString(36).slice(2);
    },

    datatables: {
        datetime_format: function (data, type, row) {
            return (moment(data).format("L [<sup>]HH:mm[</sup>]"));
        },
        date_format: function (data, type, row) {
            return (moment(data).format("L"));
        },
        time_format: function (data, type, row) {
            return (moment(data).format("HH:mm"));
        },
        percentage_format: function (data, type, row) {
            return Math.round(data) + '<sup>%</sup>';
        }
    }
};

