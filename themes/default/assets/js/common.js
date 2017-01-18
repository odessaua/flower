/**
 * Common functions
 */

$(document).ready(function() {
    // Hide flash messages block
    $(".flash_messages .close").click(function(){
        $(".flash_messages").fadeOut();
    });

});


/**
 * Update cart data after product added.
 */
function reloadSmallCart()
{
    $("#cart").load('/cart/renderSmallCart');
}

function reloadPopupCart()
{
    $("#popup-cart").load('/cart/renderPopupCart');
}

/**
 * Add product to cart from list
 * @param data
 * @param textStatus
 * @param jqXHR
 * @param redirect
 */
function processCartResponseFromList(data, textStatus, jqXHR, redirect)
{
    var productErrors = $('#productErrors');
    if(data.errors)
    {
    	$('#notavailable-modal').arcticmodal({
            overlay: {
                css: {
                    backgroundColor: '#000',
                    opacity: .5
                }
            }
        });
    	//$.jGrowl(data.errors);
        /*window.location = redirect*/
    }else{
        reloadSmallCart();
        reloadPopupCart();
        $.jGrowl("Товар успешно добавлен в корзину. <a href='/cart'>Перейти к оформлению</a>.", {position:"bottom-right"});
        
        $('#cart-modal').arcticmodal({
            overlay: {
                css: {
                    backgroundColor: '#000',
                    opacity: .5
                }
            }
        });
        
    }
}

function processCartResponseFromCart(data, textStatus, jqXHR, redirect)
{
    var productErrors = $('#productErrors');
    if(data.errors)
    {
    	$('#notavailable-modal').arcticmodal({
            overlay: {
                css: {
                    backgroundColor: '#000',
                    opacity: .5
                }
            }
        });
    	//$.jGrowl(data.errors);
        /*window.location = redirect*/
    }else{
        reloadSmallCart();
        reloadPopupCart();
        $.jGrowl("Товар успешно добавлен в корзину. <a href='/cart'>Перейти к оформлению</a>.", {position:"bottom-right"});
        location.reload();
        
    }
}
function applyInPage(el){
    console.log($(el).val());
    window.location = $(el).val();
}

function applyCategorySorter(el)
{
    window.location = $(el).val();
}
