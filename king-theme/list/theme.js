 function getCookie(name) {
                var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\/\+^])/g, '\$1') + "=([^;]*)"))
                return matches ? decodeURIComponent(matches[1]) : undefined
            }

 function changeTheme(data) {
                let elem = document.documentElement
                let attribute = elem.getAttribute("data-state")
                let themesArr = ['dark', 'light']
                if (data == undefined) {
                    let themeArrId = themesArr.indexOf(attribute)
                    attribute =  ( themeArrId == themesArr.length - 1 ) ? themesArr[0] : themesArr[themeArrId + 1]
                    console.log(themeArrId, attribute)
                    elem.setAttribute("data-state", attribute)
                    document.cookie = `themestate=${attribute};path=/;max-age=1000000`
                }
                var frame = document.getElementById("content_ifr")
                if (frame != null) {
                    var elemChildType = frame.contentWindow.document.documentElement
                    if (typeof elemChildType !== 'undefined') {
                        elemChildType.setAttribute("data-state", attribute);
                    }
                }
            }
            function createThemeCookie() {
                let themeState = 'dark'
                if(document.cookie.indexOf('themestate') == -1) {
                    document.cookie = `themestate=${themeState};path=/;max-age=1000000`
                } else {
                    themeState = getCookie('themestate')
                }
                let mainDiv = document.querySelector('html')
                mainDiv.setAttribute("data-state", themeState)
                changeTheme(themeState)

                var headerState = getCookie('headerpinned')
                if (headerState != undefined) {
                    mainDiv.setAttribute("data-visible", 'pinned')
                }
            }
          
            
            window.onload = createThemeCookie()