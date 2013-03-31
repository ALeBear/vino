//This function by http://www.movable-type.co.uk/scripts/latlong.html, under CC BY 3.0
function calculateDistance(lat1, lon1, lat2, lon2)
{
  var R = 6371; // km
  var dLat = toRad(lat2 - lat1);
  var dLon = toRad(lon2 - lon1); 
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * 
          Math.sin(dLon / 2) * Math.sin(dLon / 2); 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); 
  var d = R * c;
  return d;
}
function toRad (num) {
  return num * Math.PI / 180;
}

if (navigator.geolocation) {
    navigator.geolocation.watchPosition(geolocSuccess, geolocError);
}

var myPosition;
function geolocSuccess(position)
{
    myPosition = position;
    var closest = Array();
    $.each(allPos, function (index, pos) {
        var dist = calculateDistance(pos.lat, pos.long, position.coords.latitude, position.coords.longitude);
        if (closest.length < 3) {
            closest.push({id: pos.id, distance: dist});
        } else {
            $.each(closest, function (index, aClosePos) {
                if (dist < aClosePos.distance) {
                    closest[index] = {id: pos.id, distance: dist, label: pos.name};
                    return false;
                }
            });
        }
    });
    $.each(closest, function (index, closePos) {
        opt = $('<option/>');
        opt.val(closePos.id);
        opt.text(closePos.distance.toFixed(1) + 'km - ' + closePos.label);
        $('#optgroupClosest').append(opt);
    });
}

function geolocError(msg) {}

function createCurrentlySelected(id)
{
    $.each(allPos, function (index, pos) {
        if (pos.id == id) {
            opt = $('<option/>');
            opt.val(pos.id);
            dist = calculateDistance(pos.lat, pos.long, myPosition.coords.latitude, myPosition.coords.longitude);
            opt.text(dist.toFixed(1) + 'km - ' + pos.name);
            $('#select-avail').prepend(opt);
            $('#select-avail').val(id);
            $('#select-avail').selectmenu('refresh', true);
            return false;
        }
    });
    
}

//geoloc testing data (pos around SAQ 23214)
//geolocSuccess({coords: {latitude:"45.388563",longitude:"-73.568994"}});
