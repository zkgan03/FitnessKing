using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace ASPNET_WebAPI.Migrations
{
    /// <inheritdoc />
    public partial class AddStatus : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "IsPaid",
                table: "TransactionDetails");

            migrationBuilder.AddColumn<string>(
                name: "Status",
                table: "TransactionDetails",
                type: "TEXT",
                nullable: false,
                defaultValue: "");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "Status",
                table: "TransactionDetails");

            migrationBuilder.AddColumn<bool>(
                name: "IsPaid",
                table: "TransactionDetails",
                type: "INTEGER",
                nullable: false,
                defaultValue: false);
        }
    }
}
