using ASPNET_WebAPI.Models;

namespace ASPNET_WebAPI.Models.ViewModels
{
    public class CheckoutViewModel
    {
        public CardPaymentInfo CardPaymentInfo { get; set; } = new CardPaymentInfo();
        public CheckoutItem CheckoutItem { get; set; } = new CheckoutItem();
    }
}
