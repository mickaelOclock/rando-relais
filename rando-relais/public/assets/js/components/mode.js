const mode = {
  // Proprietes availables in the object.
  backgroundColorByDefault: null,
  backgroundColor: [],
  switch: null,
  clickedSwitch: null,
  body: null,
  headerHome: null,
  headerShared: null,
  init: function () {
    // We get the DOM elements that we need to interate with.
    // We add a listener & a handler on the click evt on each of them.
    mode.switch = document.getElementById("switch-mode-checkbox");

    if (mode.switch) {
      mode.switch.addEventListener(
        "click",
        mode.handleSelectBackgroundColorSwitch
      );
    }
    // We get all the DOM elements that will be impacted by the handleSelectBackgroundColorSwitch() method.

    // The body element.
    mode.body = document.body;

    // The headers elements.
    mode.headerHome = document.querySelector(".header-home");
    mode.headerShared = document.querySelector(".header-shared");

    // When the app is loaded we load to the page the backgroundColor wich is backup in localSatorage.
    mode.loadMode();

    // When the app is loaded we check or uncheck the switch according to the localStorage data.
    mode.handleSwitchChecked();
  },
  // Method who get the backgroundColor wich is back up in localStorage and call the switchBackgroundColor() method to change the color with the value of backgroundColor.
  loadMode: function () {
    // We get the value backup in localStorage.
    mode.backgroundColor = localStorage.getItem("mode");

    // If backgroundColor === true.
    if (mode.backgroundColor) {
      // We call the switchBackgroundColor() method to change the color with the value of backgroundColor.
      mode.switchBackgroundColor(mode.backgroundColor);
    } // Else backgroundColor === false.
    else {
      // We set a value by default to the mode key in localStorage.
      backgroundColorByDefault = localStorage.setItem("mode", "light");
      // We call the switchBackgroundColor() method to change the color with the value of backgroundColor.
      mode.switchBackgroundColor(mode.backgroundColorByDefault);
    }
  },
  handleSelectBackgroundColorSwitch: function (evt) {
    // We get the DOM element from wich the event occured.
    mode.clickedSwitch = evt.currentTarget;

    // If the mode backup in localStorage have the light value.
    if (localStorage.getItem("mode") === "light") {
      // We backup in localStorage the new value of the mode.
      localStorage.setItem("mode", "dark");
      // We set the value dark to backgroundColor.
      mode.backgroundColor = "dark";
      // We call the switchBackgroundColor() method to change the background color with the backgroundColor in argument.
      mode.switchBackgroundColor(mode.backgroundColor);
    } // Else if the mode backup in localStorage have the dark value.
    else if (localStorage.getItem("mode") === "dark") {
      // We backup in localStorage the new value of the mode.
      localStorage.setItem("mode", "light");
      // We set the value dark to backgroundColor.
      mode.backgroundColor = "light";
      // We call the switchBackgroundColor() method to change the background color with the backgroundColor in argument.
      mode.switchBackgroundColor(mode.backgroundColor);
    }
  },
  // Method who check or uncheck the switch according to the localStorage data.
  handleSwitchChecked: function () {
    // We get the value backup in localStorage.
    mode.backgroundColor = localStorage.getItem("mode");

    // If backgroundColor === true.
    if (mode.backgroundColor) {
      // If this the value of mode is light.
      if (mode.backgroundColor == "light") {
        // The switch must be not checked so we uncheck him.
        mode.switch.checked = false;
      } // Else the value of mode is dark.
      else {
        // The switch must be checked (the user check him to swtich to the dark mode) so we check him.
        mode.switch.checked = true;
      }
    } // Else we dont have a mode item in localStorage.
    else {
      // We uncheck the switch.
      mode.switch.checked = false;
    }
  },
  // Metho who switch the backgroundImage of the headers according to the localStorage data.
  switchBackgroundImage: function () {
    // We get the value backup in localStorage.
    mode.backgroundColor = localStorage.getItem("mode");

    // If headerHome === true.
    if (mode.headerHome) {
      // If backgroundColor === true.
      if (mode.backgroundColor) {
        // If this the value of mode is light.
        if (mode.backgroundColor == "light") {
          // We display the light mode backgroundImage to the headerHome.
          mode.headerHome.style.backgroundImage =
            "url('assets/images/background/background-header.jpg')";
        } // Else the value of mode is dark.
        else {
          // We display the dark mode backgroundImage to the headerHome.
          mode.headerHome.style.backgroundImage =
            "url('assets/images/background/background-header-dark-mode.jpg')";
        }
      } // Else we dont have a mode item in localStorage.
      else {
        // We display the light mode backgroundImage to the headerHome.
        mode.headerHome.style.backgroundImage =
          "url('assets/images/background/background-header.jpg')";
      }
    } // If headerShared === true.
    else if (mode.headerShared) {
      if (mode.backgroundColor) {
        // If this the value of mode is light.
        if (mode.backgroundColor == "light") {
          // We display the light mode backgroundImage to the headerShared.
          mode.headerShared.style.backgroundImage =
            "url('assets/images/background/background-header.jpg')";
        } // Else the value of mode is dark.
        else {
          // We display the dark mode backgroundImage to the headerShared.
          mode.headerShared.style.backgroundImage =
            "url('assets/images/background/background-header-dark-mode.jpg')";
        }
      } // Else we dont have a mode item in localStorage.
      else {
        // We display the light mode backgroundImage to the headerShared.
        mode.headerShared.style.backgroundImage =
          "url('assets/images/background/background-header.jpg')";
      }
    }
  },
  // Method who switch the current backgroundColor to a newBackgroumdColor.
  switchBackgroundColor: function (newBackgroundColor) {
    // We use the JS API classList to interact with the classes of the DOM elements.
    mode.body.classList.remove("dark", "light");

    // If the backgroundColor is different than the backgroundColorByDefault.
    if (newBackgroundColor !== mode.backgroundColorByDefault) {
      // We toggle the correspondent class to the body.
      mode.body.classList.add(newBackgroundColor);
    }

    // When we switch the backgroundColor we call the switchBackgroundImage() method to swtich the backgroundImage of the headers.
    mode.switchBackgroundImage();
  },
};
