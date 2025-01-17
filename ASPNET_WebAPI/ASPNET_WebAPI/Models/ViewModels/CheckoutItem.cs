using System.ComponentModel.DataAnnotations;

namespace ASPNET_WebAPI.Models.ViewModels
{
    public class CheckoutItem
    {
        [Required]
        public string TransactionId { get; set; } = string.Empty;
        public string? ItemName { get; set; } = string.Empty;
        public string? Currency { get; set; } = string.Empty;
        public decimal? Amount { get; set; } = 0;
    }
}
