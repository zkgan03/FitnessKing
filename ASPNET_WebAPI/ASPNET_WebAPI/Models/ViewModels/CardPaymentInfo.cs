
using System.ComponentModel.DataAnnotations;
using System.Security.Permissions;

namespace ASPNET_WebAPI.Models.ViewModels
{
    public class CardPaymentInfo
    {

        [Required(ErrorMessage = "Email is Required")]
        public string Email { get; set; } = string.Empty;
        [Required]
        public Card CardInfo { get; set; } = new Card();
        [Required]
        public Address BillingAddress { get; set; } = new Address();

        public class Card
        {
            [Required(ErrorMessage = "Card Holder Name is Required")]
            public string CardHolderName { get; set; } = string.Empty;
            [Required(ErrorMessage = "Card Number is required")]
            [Length(16, 19, ErrorMessage = "Card Number must be 16 digits")]
            public string CardNumber { get; set; } = string.Empty;
            [Required(ErrorMessage = "Expiry Date is required")]
            public string ExpiryDate { get; set; } = string.Empty;
            [Required(ErrorMessage = "CVV is required")]
            public string CVV { get; set; } = string.Empty;
        }

        public class Address
        {
            [Required(ErrorMessage = "Address Line 1 is required")]
            public string Line1 { get; set; } = string.Empty;
            public string? Line2 { get; set; }
            [Required(ErrorMessage = "City is required")]
            public string City { get; set; } = string.Empty;
            [Required(ErrorMessage = "Country is required")]
            public string Country { get; set; } = string.Empty;
            [Required(ErrorMessage = "PostCode is required")]
            public string Postcode { get; set; } = string.Empty;
        }
    }


}
