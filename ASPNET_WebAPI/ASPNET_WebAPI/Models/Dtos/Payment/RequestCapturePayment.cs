using System.ComponentModel.DataAnnotations;
using System.Text.Json.Serialization;

namespace ASPNET_WebAPI.Models.Dtos.Payment
{
    public class RequestCapturePayment
    {
        [Required]
        [JsonPropertyName("transaction_id")]
        public string TransactionId { get; set; } = string.Empty;
    }
}
