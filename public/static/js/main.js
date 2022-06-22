// 设置导航滚动事件
$(window).scroll(function () {
    let scroH = $(this).scrollTop()
    let contentH = $('.channel').height() || $('#carouselExampleCaptions').height() || 0
    scroH > contentH ? $('#toTop').show() : $('#toTop').hide()
    if ($('.channel').length || $('#carouselExampleCaptions').length) {
        scroH ? $('nav.navbar').addClass('bg-primary ') : $('nav.navbar').removeClass('bg-primary ')
    }
})
$(document).ready(function () {
    // 区分设备执行
    if ((/iPad|iPhone|Android/i).test(navigator.userAgent)) {
        // Tab窗口切换方式
        tabEventListener('#productTab button', 'click')
        // 监听二级菜单点击事件
        $('.accordion dt').on('click', function (e) {
            e.stopPropagation()
            let _this = $(this)
            _this.parents('li.list-group-item').siblings().children().removeClass('selected')
            _this.parents('li.list-group-item').siblings().find('dt').removeClass('iconRotate')
            _this.parent().toggleClass('selected')
            _this.toggleClass('iconRotate')
        })
    } else { // 电脑端
        // Tab窗口切换方式
        tabEventListener('#productTab button')
        // 导航栏hover事件
        $('li.dropdown').hover(function () {
            $(this).children('.dropdown-menu').toggleClass('show')
        })
    }
    // 对应页面导航栏样式
    location.pathname !== '/' && $('li.nav-item').each(function () {
        let curPath = location.href
        let parentPath = $(this).children().attr('href')
        curPath.indexOf(parentPath) >= 1 && $(this).toggleClass('active')
    })
    // 监听提交按钮
    const myToastEl = document.getElementById('liveToast')
    myToastEl.addEventListener('hidden.bs.toast', function () {
        const submit = document.getElementsByClassName('submit')
        submit.length && submit[0].removeAttribute('disabled')
    })
    // 客服组件部分
    $('.part-service').find('img').hover(function () {
        $(this).next().show(400)
        $(this).attr('src', $(this).data('original'))
    }, function () {
        switch ($(this).attr('id')) {
            case 'qq':
                $(this).attr('src', '//static.brandsz.cn/static/icon/qq0.svg')
                break
            case 'tel':
                $(this).attr('src', '//static.brandsz.cn/static/icon/tel.svg')
                break
            case 'wechat':
                $(this).attr('src', '//static.brandsz.cn/static/icon/wechat.svg')
                break
            default:
                console.log('返回顶部')
        }
        $(this).next().hide('fast')
    })
    // 产品中心导航
    $(function () {
        let smImg
        $('.part-product-category-item').hover(function () {
            $(this).css({
                color: 'white',
                backgroundColor: '#eb891a'
            })
            smImg = $(this).find('img').attr('src')
            let bigImg = $(this).find('img').data('origin')
            $(this).find('img').attr('src', bigImg)
        }, function () {
            $(this).removeAttr('style')
            $(this).find('img').attr('src', smImg)
        })
    })
    // 产品相册切换
    $('.part-produce-item').hover(function () {
        setThunmBorder($(this).index())
        let alt = $(this).children().attr('alt')
        let title = $(this).children().attr('title')
        let bigImg = $(this).children().data('origin')
        let bigImgEl = $('.part-produce-bigimg')
        bigImgEl.attr('alt', alt)
        bigImgEl.attr('src', bigImg)
        bigImgEl.attr('title', title)
    })
    // 上/下相册切换
    let album = []
    $('.part-produce-item > img').each(function () {
        // album.push($(this).data('origin'))
        album.push({
            'origin': $(this).data('origin'),
            'alt': $(this).attr('alt'),
            'title': $(this).attr('title')
        })
    })
    $('.prenext').click(function () {
        let action = $(this).data('slide')
        let eleBigImg = $('.part-produce-bigimg')
        let bigImg = eleBigImg.attr('src')
        switch (action) {
            case 'ctprev': // 上一张
                for (let i = 0; i < album.length; i ++) {
                    // FIXME:有bug需要修复
                    if ((album[i].origin === bigImg) && (album.length - i)) {
                        setThunmBorder(i - 1)
                        eleBigImg.attr('src', album[i - 1].origin)
                        eleBigImg.attr('alt', album[i - 1].alt)
                        eleBigImg.attr('title', album[i - 1].title)
                    }
                }
                break
            case 'ctnext': // 下一张
                for (let i = 0; i < album.length; i ++) {
                    if ((album[i].origin === bigImg) && (i + 1) < album.length) {
                        setThunmBorder(i + 1)
                        eleBigImg.attr('src', album[i + 1].origin)
                        eleBigImg.attr('alt', album[i + 1].alt)
                        eleBigImg.attr('title', album[i + 1].title)
                    }
                }
                break
            default: return false
        }
    })
    // 公共表单验证
    $(function () {
        $('#name').blur(function () {
            if (!$(this).val()) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).addClass('is-invalid')
            } else if (!$(this).val().match(/^[\u4e00-\u9fa5]+$/)) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).next('div.invalid-feedback')[0].innerHTML = '阿鬼，你还是说中文吧'
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })
        $('#mobile').blur(function () {
            if (!$(this).val()) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).addClass('is-invalid')
            } else if ($(this).val().length < 11) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).next('div.invalid-feedback')[0].innerHTML = `没有${$(this).val().length}位的手机号`
                $(this).addClass('is-invalid')
            } else if (!/^1[3456789]\d{9}$/.test($(this).val())) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).next('div.invalid-feedback')[0].innerHTML = '手机号码错误，请认真填写'
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })
        $('#email').blur(function () {
            if (!$(this).val()) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).addClass('is-invalid')
            } else if (!/^([a-zA-Z]|[0-9])(\w|-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test($(this).val())) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).next('div.invalid-feedback')[0].innerHTML = '邮箱格式错误，请重新填写'
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })
        $('#message').blur(function () {
            if (!$(this).val()) {
                $(this).hasClass('is-valid') && $(this).removeClass('is-valid')
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })
    })
})
// goToTop
function goToTop() {
    document.scrollingElement.scrollTop = 0
}
// 在线咨询
function consulting() {
    const shangqiao = $('#nb_invite_ok')
    shangqiao.length > 0 ? shangqiao.click() : alert('百度商桥尚未初始化')
}
// 设置缩略图
function setThunmBorder(idx) {
    $('.part-produce-item').removeClass('border-danger')
    $('.part-produce-item:eq(' +idx+ ')').addClass('border-danger')
}
/**
 * @note Tab窗口切换
 * @param documentEl string DOM元素
 * @param userAction string 触发方式
 */
function tabEventListener(documentEl, userAction = 'mouseenter') {
    // 将DOM元素转化为数组用于后面的遍历
    const triggerTabList = [].slice.call(document.querySelectorAll(documentEl))
    triggerTabList.forEach((item) => {
        const tabTrigger = new bootstrap.Tab(item)
        item.addEventListener(userAction, function (e) {
            tabTrigger.show()
            e.preventDefault()
        })
    })
}
// 公共表单提交
function commitForm(currentForm) {
    const subStatus = $('.submit')
    const myToast = $('#liveToast')
    // 避免重复提交
    subStatus.attr('disabled', 'true')
    $.ajax({
        type: 'POST',
        url: '/console/public/submit',
        data: $(currentForm).serialize(),
        success: (r) => {
            if (r.status === 200) {
                $('#formModal') && $('#formModal').modal('hide')
            }
            myToast.toast('show')
            myToast.find('.me-auto').html(r.status)
            myToast.find('.toast-body').html(r.msg)
        }, error: (e) => {
            myToast.toast('show')
            myToast.find('.me-auto').html(e.status)
            myToast.find('.toast-message').html(e.msg)
        }
    })
}
/** 表单留言部分 Start */
window.onload = function () {
    const myModalEl = document.getElementById('formModal')
    myModalEl &&　myModalEl.addEventListener('show.bs.modal', function () {
        let html = ''
        const city = $('#inputCity')
        const district = $('#inputDistrict')
        const provinces = $('#inputProvinces')
        city.append(html)
        district.append(html)
        $.each(addr, (idx, item) => {
            html += "<option data-code="+idx+" value=" +item.name+">" +item.name+ "</options>"
        })
        provinces.append(html)
        provinces.change(function () {
            if ($(this).val() === '') return
            $("#inputCity option").remove()
            $("#inputDistrict option").remove()
            let code = $(this).find("option:selected").data('code').toString()
            let html = "<option value=''>--请选择--</option>"
            district.append(html)
            $.each(addr,function(idx, item){
                if (code === idx) {
                    $.each(item.child, (cid, city) => {
                        html += "<option data-code="+cid+" value=" +city.name+">" +city.name+ "</options>"
                    })
                }
            })
            city.append(html)
        })
        city.change(function () {
            if ($(this).val() === "") return
            $("#inputDistrict option").remove()
            let code = $(this).find("option:selected").data('code').toString()
            let html = "<option value=''>--请选择--</option>"
            $.each(addr,function(idx, item) {
                $.each(item.child, function (cid, city) {
                    if (code === cid) {
                        $.each(city.child, (tid, district) => {
                            html +="<option value="+district+">"+ district +"</option>"
                        })
                    }
                })
            })
            district.append(html)
        })

        // 验证姓名
        $('#username').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else if (!$(this).val().match(/^[\u4e00-\u9fa5]+$/)) {
                $(this).next('div.invalid-feedback')[0].innerHTML = '阿鬼，你还是说中文吧'
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })

        // 验证手机
        $('#mobileNum').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else if ($(this).val().length < 11) {
                $(this).next('div.invalid-feedback')[0].innerHTML = `没有${$(this).val().length}位的手机号`
                $(this).addClass('is-invalid')
            } else if (!/^1[3456789]\d{9}$/.test($(this).val())) {
                $(this).next('div.invalid-feedback')[0].innerHTML = '手机号码错误，请认真填写'
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })

        // 验证公司
        $('#company').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })

        // 验证邮箱
        $('#emailAddr').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else if (!/^([a-zA-Z]|[0-9])(\w)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test($(this).val())) {
                $(this).next('div.invalid-feedback')[0].innerHTML = '邮箱格式错误，请重新填写'
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })

        // 验证省份
        $('#inputProvinces').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })

        // 验证城市
        $('#inputCity').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })

        // 验证区域
        $('#inputDistrict').blur(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid')
            } else {
                $(this).hasClass('is-invalid') && $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
            }
        })
    })
}
/** 表单留言部分 End */
