using System.ComponentModel.DataAnnotations;
using System.Text.Json.Serialization;

namespace ASPNET_WebAPI.Models.Dtos.Payment
{
    public class RequestPaymentDto
    {
        [JsonPropertyName("reference_id")]
        public string ReferenceId { get; set; } = string.Empty;
        [JsonPropertyName("custom_id")]
        public string CustomId { get; set; } = string.Empty;

        [Required]
        [JsonPropertyName("item_name")]
        public string ItemName { get; set; } = string.Empty;
        [JsonPropertyName("currency")]
        public string Currency { get; set; } = "USD";
        [JsonPropertyName("description")]
        public string Description { get; set; } = string.Empty;

        [Required]
        [JsonPropertyName("amount")]
        public decimal Amount { get; set; } = 0;
        [Required]
        [JsonPropertyName("success_url")]
        public string SuccessUrl { get; set; } = string.Empty;
        [Required]
        [JsonPropertyName("cancel_url")]
        public string CancelUrl { get; set; } = string.Empty;
    }
}
