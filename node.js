var request = new XMLHttpRequest();
request.open("GET", "binondo.json");

request.onload = function () {
  if (request.status !== 200) return;

  var parsedData = JSON.parse(request.response);
  var items = parsedData.data || [];
  var list = document.getElementById("api-adventures");

  for (var i = 0; i < items.length; i++) {
    var place = items[i];
    var li = document.createElement("li");
    li.textContent = place.name || "No title";
    list.appendChild(li);
  }
};

request.send();
